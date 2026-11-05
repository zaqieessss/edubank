<?php

namespace App\Services;

use App\Enums\Major;
use App\Models\Member;
use App\Models\Saver;
use Exception;
use Illuminate\Support\Facades\DB;

class MemberService
{
    protected SpreadsheetService $spreadsheetService;

    public function __construct(?SpreadsheetService $spreadsheetService = null)
    {
        $this->spreadsheetService = $spreadsheetService ?? new SpreadsheetService();
    }

    /**
     * Sample student template definitions.
     */
    protected function getTemplateData(): array
    {
        $headers = [
            'student_id_number',
            'full_name',
            'grade_level',
            'class_name',
            'major',
            'cohort',
            'gender',
            'email',
            'phone_number',
            'address',
        ];

        $samples = [
            [
                '13001',
                'Muhammad Rizqi Pratama',
                '10',
                'X RPL 1',
                'RPL',
                date('Y'),
                'male',
                'm.rizqi@siswa.edubank.test',
                '081234567801',
                'Bantul, DIY',
            ],
            [
                '13002',
                'Anisa Rahmawati',
                '10',
                'X AKL 1',
                'AKL',
                date('Y'),
                'female',
                'anisa.r@siswa.edubank.test',
                '081234567802',
                'Sleman, DIY',
            ],
            [
                '13003',
                'Dimas Bagus Wicaksono',
                '10',
                'X TKJ 1',
                'TKJ',
                date('Y'),
                'male',
                'dimas.b@siswa.edubank.test',
                '081234567803',
                'Yogyakarta',
            ],
        ];

        return [$headers, $samples];
    }

    /**
     * Generate official sample CSV template for school student import.
     */
    public function generateTemplateCsv(): string
    {
        [$headers, $samples] = $this->getTemplateData();

        $output = fopen('php://temp', 'r+');
        fputcsv($output, $headers);
        foreach ($samples as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }

    /**
     * Generate official sample Excel (.xlsx) template for school student import.
     */
    public function generateTemplateXlsx(): string
    {
        [$headers, $samples] = $this->getTemplateData();
        return $this->spreadsheetService->generateXlsx($headers, $samples);
    }

    /**
     * Import multiple students from CSV or Excel file into members table.
     *
     * @param string $filePath
     * @return array Summary of import ['imported' => count, 'skipped' => count, 'errors' => []]
     * @throws Exception
     */
    public function importFile(string $filePath): array
    {
        $rows = $this->spreadsheetService->parse($filePath);

        if (empty($rows)) {
            throw new Exception('Berkas Excel / CSV kosong atau tidak ada data yang ditemukan.');
        }

        $rawHeader = array_shift($rows);
        if (empty($rawHeader)) {
            throw new Exception('Baris pertama (header kolom) kosong.');
        }

        // Map column aliases to standard fields
        $aliasMap = [
            'student_id_number' => 'student_id_number',
            'nis'               => 'student_id_number',
            'nisn'              => 'student_id_number',
            'no_induk'          => 'student_id_number',
            'nomor_induk'       => 'student_id_number',

            'full_name'         => 'full_name',
            'nama'              => 'full_name',
            'nama_lengkap'      => 'full_name',
            'nama_siswa'        => 'full_name',
            'name'              => 'full_name',

            'grade_level'       => 'grade_level',
            'tingkat'           => 'grade_level',
            'tingkat_kelas'     => 'grade_level',
            'kelas_tingkat'     => 'grade_level',

            'class_name'        => 'class_name',
            'kelas'             => 'class_name',
            'rombel'            => 'class_name',
            'nama_kelas'        => 'class_name',

            'major'             => 'major',
            'jurusan'           => 'major',
            'program_keahlian'  => 'major',
            'kompetensi_keahlian'=> 'major',

            'cohort'            => 'cohort',
            'angkatan'          => 'cohort',
            'tahun_masuk'       => 'cohort',
            'tahun_ajaran'      => 'cohort',

            'gender'            => 'gender',
            'jenis_kelamin'     => 'gender',
            'jk'                => 'gender',

            'email'             => 'email',
            'surel'             => 'email',
            'e-mail'            => 'email',

            'phone_number'      => 'phone_number',
            'telepon'           => 'phone_number',
            'no_hp'             => 'phone_number',
            'no_telp'           => 'phone_number',
            'hp'                => 'phone_number',

            'address'           => 'address',
            'alamat'            => 'address',
        ];

        $headerMap = [];
        foreach ($rawHeader as $idx => $colName) {
            $cleaned = strtolower(trim((string) preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $colName)));
            $cleaned = str_replace([' ', '-', '.'], '_', $cleaned);
            $canonical = $aliasMap[$cleaned] ?? $cleaned;
            $headerMap[$idx] = $canonical;
        }

        $imported = 0;
        $skipped  = 0;
        $errors   = [];
        $rowNumber = 1;

        $validMajors = Major::values();

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $rowNumber++;

                if (empty(array_filter($row, fn($v) => trim((string)$v) !== ''))) {
                    continue; // Skip empty rows
                }

                $data = [];
                foreach ($headerMap as $idx => $key) {
                    $data[$key] = isset($row[$idx]) ? trim((string)$row[$idx]) : null;
                }

                $nis = $data['student_id_number'] ?? null;
                $email = $data['email'] ?? null;
                $fullName = $data['full_name'] ?? null;
                $major = strtoupper($data['major'] ?? '');
                if ($major === 'AK') {
                    $major = 'AKL';
                }
                if ($major === 'MPLB') {
                    $major = 'MP';
                }
                if ($major === 'BDP') {
                    $major = 'BR';
                }
                $gradeLevel = (string) ($data['grade_level'] ?? '10');
                $gradeLevel = str_replace(['kelas', 'tingkat', ' '], '', strtolower($gradeLevel));
                if (!in_array($gradeLevel, ['10', '11', '12', 'alumni'], true)) {
                    $gradeLevel = '10';
                }

                $className = $data['class_name'] ?? ('X ' . $major . ' 1');
                $cohort = trim((string)($data['cohort'] ?? ''));
                if (empty($cohort) || in_array($cohort, ['2026', '2025', '2024'], true)) {
                    $cohort = match ($gradeLevel) {
                        '12' => '27',
                        '11' => '28',
                        default => '29',
                    };
                }
                
                $rawGender = strtolower($data['gender'] ?? 'male');
                $gender = (in_array($rawGender, ['female', 'perempuan', 'p', 'f'], true)) ? 'female' : 'male';
                
                $phone = $data['phone_number'] ?? null;
                $address = $data['address'] ?? null;

                if (!$nis || !$fullName || !$email) {
                    $errors[] = "Baris #{$rowNumber}: NIS, Nama Lengkap, dan Email wajib diisi.";
                    $skipped++;
                    continue;
                }

                // Check major validity
                if (!in_array($major, $validMajors)) {
                    $errors[] = "Baris #{$rowNumber}: Jurusan '{$major}' tidak valid. Pilihan resmi: " . implode(', ', $validMajors);
                    $skipped++;
                    continue;
                }

                // Check for existing NIS or Email
                $exists = Member::where('student_id_number', $nis)->orWhere('email', $email)->exists();
                if ($exists) {
                    $skipped++;
                    continue;
                }

                Member::create([
                    'student_id_number'   => $nis,
                    'full_name'           => $fullName,
                    'grade_level'         => $gradeLevel,
                    'class_name'          => $className,
                    'major'               => $major,
                    'cohort'              => $cohort,
                    'gender'              => $gender,
                    'email'               => $email,
                    'phone_number'        => $phone,
                    'address'             => $address,
                    'registration_status' => 'unregistered',
                ]);

                $imported++;
            }

            DB::commit();

            return [
                'imported' => $imported,
                'skipped'  => $skipped,
                'errors'   => $errors,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Backward-compatible alias for importFile.
     */
    public function importCsv(string $filePath): array
    {
        return $this->importFile($filePath);
    }

    /**
     * Mass Grade Promotion (Fitur Naik Kelas Massal SMKN 1 Bantul).
     * 12 -> alumni
     * 11 -> 12 (XI RPL 1 -> XII RPL 1)
     * 10 -> 11 (X RPL 1 -> XI RPL 1)
     *
     * @param array $excludedMemberIds List of member IDs that should stay in their current grade (tidak naik kelas)
     * @return array Summary of updated records
     */
    public function promoteGrades(array $excludedMemberIds = []): array
    {
        return DB::transaction(function () use ($excludedMemberIds) {
            // Filter excluded IDs to integers
            $excludedMemberIds = array_filter(array_map('intval', $excludedMemberIds));

            // 1. Grade 12 -> Alumni (Ubah status siswa dan saver menjadi nonaktif/alumni, TIDAK dihapus)
            $twelfthQuery = Member::where('grade_level', '12');
            if (!empty($excludedMemberIds)) {
                $twelfthQuery->whereNotIn('id', $excludedMemberIds);
            }
            $twelfthMembers = $twelfthQuery->get();
            $twelfthCount = $twelfthMembers->count();
            $twelfthMemberIds = $twelfthMembers->pluck('id');

            Member::whereIn('id', $twelfthMemberIds)->update([
                'grade_level' => 'alumni',
            ]);

            // Ubah status saver menjadi inactive dan akun dibekukan/dinonaktifkan
            $savers = Saver::whereIn('member_id', $twelfthMemberIds)->get();
            foreach ($savers as $saver) {
                $saver->update([
                    'grade_level' => 'alumni',
                    'status'      => 'inactive',
                ]);

                // Nonaktifkan user login agar alumni tidak bisa melakukan transaksi loket lagi
                if ($saver->user) {
                    $saver->user->update(['status' => 'inactive']);
                }
            }

            // 2. Grade 11 -> Grade 12
            $eleventhQuery = Member::where('grade_level', '11');
            if (!empty($excludedMemberIds)) {
                $eleventhQuery->whereNotIn('id', $excludedMemberIds);
            }
            $eleventhMembers = $eleventhQuery->get();
            $eleventhCount = $eleventhMembers->count();
            foreach ($eleventhMembers as $m) {
                $newClassName = preg_replace('/^XI\b/i', 'XII', $m->class_name);
                $m->update([
                    'grade_level' => '12',
                    'class_name'  => $newClassName,
                ]);

                // Sync saver if exists
                if ($m->saver) {
                    $m->saver->update([
                        'grade_level' => '12',
                        'class_name'  => $newClassName,
                    ]);
                }
            }

            // 3. Grade 10 -> Grade 11
            $tenthQuery = Member::where('grade_level', '10');
            if (!empty($excludedMemberIds)) {
                $tenthQuery->whereNotIn('id', $excludedMemberIds);
            }
            $tenthMembers = $tenthQuery->get();
            $tenthCount = $tenthMembers->count();
            foreach ($tenthMembers as $m) {
                $newClassName = preg_replace('/^X\b/i', 'XI', $m->class_name);
                $m->update([
                    'grade_level' => '11',
                    'class_name'  => $newClassName,
                ]);

                // Sync saver if exists
                if ($m->saver) {
                    $m->saver->update([
                        'grade_level' => '11',
                        'class_name'  => $newClassName,
                    ]);
                }
            }

            return [
                'promoted_to_alumni' => $twelfthCount,
                'promoted_to_12'     => $eleventhCount,
                'promoted_to_11'     => $tenthCount,
                'total_affected'     => $twelfthCount + $eleventhCount + $tenthCount,
                'excluded_count'     => count($excludedMemberIds),
            ];
        });
    }
}
