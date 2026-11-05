<?php

namespace App\Services;

use Exception;
use ZipArchive;

class SpreadsheetService
{
    /**
     * Parse spreadsheet file (CSV, XLSX, or XLS) into a 2D array of rows.
     *
     * @param string $filePath
     * @return array
     * @throws Exception
     */
    public function parse(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new Exception("Berkas tidak ditemukan: {$filePath}");
        }

        // Check if file is ZIP/OpenXML (.xlsx)
        $handle = fopen($filePath, 'rb');
        $magic = fread($handle, 4);
        fclose($handle);

        if ($magic === "PK\x03\x04") {
            return $this->parseXlsx($filePath);
        }

        // Check if it's XML Spreadsheet or HTML table (often saved as .xls)
        $sample = file_get_contents($filePath, false, null, 0, 1024);
        if (stripos($sample, '<?xml') !== false && stripos($sample, 'Workbook') !== false) {
            return $this->parseXmlSpreadsheet($filePath);
        }

        if (stripos($sample, '<table') !== false || stripos($sample, '<html') !== false) {
            return $this->parseHtmlTable($filePath);
        }

        // Default: parse as CSV with auto delimiter detection
        return $this->parseCsv($filePath);
    }

    /**
     * Parse OpenXML Excel (.xlsx) using native PHP ZipArchive & SimpleXML.
     */
    public function parseXlsx(string $filePath): array
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new Exception("Gagal membuka berkas Excel (.xlsx). Pastikan berkas tidak rusak.");
        }

        // 1. Read shared strings
        $sharedStrings = [];
        $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedStringsXml !== false) {
            $xml = @simplexml_load_string($sharedStringsXml);
            if ($xml) {
                foreach ($xml->si as $si) {
                    if (isset($si->t)) {
                        $sharedStrings[] = (string) $si->t;
                    } elseif (isset($si->r)) {
                        $text = '';
                        foreach ($si->r as $r) {
                            $text .= (string) $r->t;
                        }
                        $sharedStrings[] = $text;
                    } else {
                        $sharedStrings[] = '';
                    }
                }
            }
        }

        // 2. Locate worksheet (sheet1.xml or first available sheet)
        $sheetXmlContent = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($sheetXmlContent === false) {
            for ($i = 1; $i <= 10; $i++) {
                $sheetXmlContent = $zip->getFromName("xl/worksheets/sheet{$i}.xml");
                if ($sheetXmlContent !== false) break;
            }
        }

        if ($sheetXmlContent === false) {
            $zip->close();
            throw new Exception("Lembar kerja tidak ditemukan di dalam berkas Excel.");
        }

        $sheetXml = @simplexml_load_string($sheetXmlContent);
        $zip->close();

        if (!$sheetXml || !isset($sheetXml->sheetData)) {
            throw new Exception("Format data lembar kerja Excel tidak dapat dibaca.");
        }

        $rows = [];
        foreach ($sheetXml->sheetData->row as $row) {
            $rowData = [];
            $maxCol = 0;

            foreach ($row->c as $cell) {
                $r = (string) $cell['r'];
                $colName = preg_replace('/[0-9]/', '', $r);
                $colIdx = 0;
                $len = strlen($colName);
                for ($k = 0; $k < $len; $k++) {
                    $colIdx = $colIdx * 26 + (ord(strtoupper($colName[$k])) - 64);
                }
                $colIdx -= 1;

                $type = (string) $cell['t'];
                $val = '';

                if ($type === 's') {
                    $ssIdx = (int) $cell->v;
                    $val = $sharedStrings[$ssIdx] ?? '';
                } elseif ($type === 'inlineStr' && isset($cell->is->t)) {
                    $val = (string) $cell->is->t;
                } elseif (isset($cell->v)) {
                    $val = (string) $cell->v;
                }

                $rowData[$colIdx] = trim($val);
                if ($colIdx > $maxCol) {
                    $maxCol = $colIdx;
                }
            }

            $fullRow = [];
            for ($c = 0; $c <= $maxCol; $c++) {
                $fullRow[$c] = $rowData[$c] ?? '';
            }

            // Exclude completely empty rows
            if (!empty(array_filter($fullRow, fn($v) => $v !== ''))) {
                $rows[] = $fullRow;
            }
        }

        return $rows;
    }

    /**
     * Parse CSV / TXT files with auto-detect delimiter and UTF-8 BOM stripping.
     */
    public function parseCsv(string $filePath): array
    {
        $content = file_get_contents($filePath);
        $bom = pack('H*', 'EFBBBF');
        $content = preg_replace("/^$bom/", '', $content);

        $firstLine = strtok($content, "\r\n");
        $commaCount = substr_count($firstLine, ',');
        $semicolonCount = substr_count($firstLine, ';');
        $tabCount = substr_count($firstLine, "\t");

        $delimiter = ',';
        if ($semicolonCount > $commaCount && $semicolonCount > $tabCount) {
            $delimiter = ';';
        } elseif ($tabCount > $commaCount && $tabCount > $semicolonCount) {
            $delimiter = "\t";
        }

        $file = fopen('php://memory', 'r+');
        fwrite($file, $content);
        rewind($file);

        $rows = [];
        while (($row = fgetcsv($file, 0, $delimiter)) !== false) {
            if (!empty(array_filter($row, fn($v) => trim($v) !== ''))) {
                $rows[] = array_map('trim', $row);
            }
        }
        fclose($file);

        return $rows;
    }

    /**
     * Parse XML Spreadsheet (Excel 2003 XML).
     */
    protected function parseXmlSpreadsheet(string $filePath): array
    {
        $content = file_get_contents($filePath);
        $xml = @simplexml_load_string($content);
        if (!$xml) {
            return $this->parseCsv($filePath);
        }

        $ns = $xml->getNamespaces(true);
        $ssNs = $ns['ss'] ?? 'urn:schemas-microsoft-com:office:spreadsheet';
        $xml->registerXPathNamespace('ss', $ssNs);

        $rows = [];
        $xmlRows = $xml->xpath('//ss:Row');
        foreach ($xmlRows as $row) {
            $cells = $row->xpath('ss:Cell/ss:Data');
            $rowData = [];
            foreach ($cells as $data) {
                $rowData[] = trim((string) $data);
            }
            if (!empty(array_filter($rowData, fn($v) => $v !== ''))) {
                $rows[] = $rowData;
            }
        }

        return !empty($rows) ? $rows : $this->parseCsv($filePath);
    }

    /**
     * Parse HTML table saved as .xls.
     */
    protected function parseHtmlTable(string $filePath): array
    {
        $content = file_get_contents($filePath);
        $dom = new \DOMDocument();
        @$dom->loadHTML($content);

        $rows = [];
        $trNodes = $dom->getElementsByTagName('tr');
        foreach ($trNodes as $tr) {
            $rowData = [];
            foreach ($tr->childNodes as $child) {
                if ($child->nodeName === 'td' || $child->nodeName === 'th') {
                    $rowData[] = trim($child->textContent);
                }
            }
            if (!empty(array_filter($rowData, fn($v) => $v !== ''))) {
                $rows[] = $rowData;
            }
        }

        return !empty($rows) ? $rows : $this->parseCsv($filePath);
    }

    /**
     * Generate an Excel (.xlsx) file in binary format.
     */
    public function generateXlsx(array $headers, array $data): string
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'xlsx_');
        $zip = new ZipArchive();
        if ($zip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new Exception("Gagal membuat arsip XLSX sementara.");
        }

        $allRows = array_merge([$headers], $data);
        $sharedStrings = [];
        $stringIndex = [];

        $sheetDataXml = '';
        $rowIdx = 1;

        foreach ($allRows as $row) {
            $sheetDataXml .= '<row r="' . $rowIdx . '">';
            $colIdx = 0;
            foreach ($row as $val) {
                $colLetter = '';
                $temp = $colIdx + 1;
                while ($temp > 0) {
                    $rem = ($temp - 1) % 26;
                    $colLetter = chr(65 + $rem) . $colLetter;
                    $temp = intdiv($temp - 1, 26);
                }
                $cellRef = $colLetter . $rowIdx;

                $valStr = (string)$val;
                if (!isset($stringIndex[$valStr])) {
                    $stringIndex[$valStr] = count($sharedStrings);
                    $sharedStrings[] = $valStr;
                }
                $sIdx = $stringIndex[$valStr];
                $sheetDataXml .= '<c r="' . $cellRef . '" t="s"><v>' . $sIdx . '</v></c>';
                $colIdx++;
            }
            $sheetDataXml .= '</row>';
            $rowIdx++;
        }

        $sstXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $sstXml .= '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="' . count($sharedStrings) . '" uniqueCount="' . count($sharedStrings) . '">';
        foreach ($sharedStrings as $str) {
            $sstXml .= '<si><t>' . htmlspecialchars($str, ENT_XML1, 'UTF-8') . '</t></si>';
        }
        $sstXml .= '</sst>';

        $wsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $wsXml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' . "\n";
        $wsXml .= '<sheetData>' . $sheetDataXml . '</sheetData>' . "\n";
        $wsXml .= '</worksheet>';

        $ctXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $ctXml .= '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">' . "\n";
        $ctXml .= '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>' . "\n";
        $ctXml .= '<Default Extension="xml" ContentType="application/xml"/>' . "\n";
        $ctXml .= '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>' . "\n";
        $ctXml .= '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>' . "\n";
        $ctXml .= '<Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>' . "\n";
        $ctXml .= '</Types>';

        $relsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $relsXml .= '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' . "\n";
        $relsXml .= '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>' . "\n";
        $relsXml .= '</Relationships>';

        $wbRelsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $wbRelsXml .= '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' . "\n";
        $wbRelsXml .= '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>' . "\n";
        $wbRelsXml .= '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>' . "\n";
        $wbRelsXml .= '</Relationships>';

        $wbXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $wbXml .= '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">' . "\n";
        $wbXml .= '<sheets><sheet name="Data Siswa" sheetId="1" r:id="rId1"/></sheets>' . "\n";
        $wbXml .= '</workbook>';

        $zip->addFromString('[Content_Types].xml', $ctXml);
        $zip->addFromString('_rels/.rels', $relsXml);
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRelsXml);
        $zip->addFromString('xl/workbook.xml', $wbXml);
        $zip->addFromString('xl/sharedStrings.xml', $sstXml);
        $zip->addFromString('xl/worksheets/sheet1.xml', $wsXml);

        $zip->close();

        $content = file_get_contents($tempPath);
        @unlink($tempPath);

        return $content;
    }
}
