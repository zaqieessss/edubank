<?php

namespace App\Services;

use App\Enums\Major;
use App\Models\Member;
use App\Models\Saver;
use Exception;
use Illuminate\Support\Facades\DB;

class MemberService
{
    /**
     * Generate official sample CSV template for school student import.
     */
    public function generateTemplateCsv(): string
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
     * Import multiple students from CSV file into members table.
     *
     * @param string $filePath
     * @return array Summary of import ['imported' => count, 'skipped' => count, 'errors' => []]
     * @throws Exception
     */
    public function importCsv(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new Exception("File CSV tidak ditemukan: {$filePath}");
        }

        $content = file_get_contents($filePath);
        // Strip UTF-8 BOM if present (added by Excel)
        $bom = pack('H*', 'EFBBBF');
        $content = preg_replace("/^$bom/", '', $content);

        // Auto-detect delimiter: count commas vs semicolons in the first line
        $firstLine = strtok($content, "\r\n");
        $commaCount = substr_count($firstLine, ',');
        $semicolonCount = substr_count($firstLine, ';');
        $delimiter = ($semicolonCount > $commaCount) ? ';' : ',';

        $file = fopen('php://memory', 'r+');
        fwrite($file, $content);
        rewind($file);

        $header = fgetcsv($file, 0, $delimiter);

        if (!$header) {
            fclose($file);
            throw new Exception('File CSV kosong atau tidak valid.');
        }

        // Normalize header names (lowercase, strip BOM/spaces)
        $headerMap = array_map(function ($col) {
            $cleaned = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $col);
            return strtolower(trim($cleaned));
        }, $header);

        $imported = 0;
        $skipped  = 0;
        $errors   = [];
        $rowNumber = 1;

        $validMajors = Major::values();

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($file, 0, $delimiter)) !== false) {
                $rowNumber++;

                if (count($row) < count($headerMap) || empty(array_filter($row))) {
                    continue; // Skip empty rows
                }

                $data = array_combine($headerMap, array_map('trim', $row));

                $nis = $data['student_id_number'] ?? null;
                $email = $data['email'] ?? null;
                $fullName = $data['full_name'] ?? null;
                $major = strtoupper($data['major'] ?? '');
                $gradeLevel = $data['grade_level'] ?? '10';
                $className = $data['class_name'] ?? 'X ' . $major . ' 1';
                $cohort = $data['cohort'] ?? date('Y');
                $gender = strtolower($data['gender'] ?? 'male') === 'female' ? 'female' : 'male';
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
                    'student_id_number' => $nis,
                    'full_name' => $fullName,
                    'grade_level' => in_array($gradeLevel, ['10', '11', '12', 'alumni']) ? $gradeLevel : '10',
                    'class_name' => $className,
                    'major' => $major,
                    'cohort' => $cohort,
                    'gender' => $gender,
                    'email' => $email,
                    'phone_number' => $phone,
                    'address' => $address,
                    'registration_status' => 'unregistered',
                ]);

                $imported++;
            }

            DB::commit();
            fclose($file);

            return [
                'imported' => $imported,
                'skipped'  => $skipped,
                'errors'   => $errors,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            fclose($file);
            throw $e;
        }
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
