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

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);

        if (!$header) {
            fclose($file);
            throw new Exception('File CSV kosong atau tidak valid.');
        }

        // Normalize header names (lowercase & trimmed)
        $headerMap = array_map(fn($col) => strtolower(trim($col)), $header);

        $imported = 0;
        $skipped  = 0;
        $errors   = [];
        $rowNumber = 1;

        $validMajors = Major::values();

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($file)) !== false) {
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
     * @return array Summary of updated records
     */
    public function promoteGrades(): array
    {
        return DB::transaction(function () {
            // 1. Grade 12 -> Alumni
            $twelfthCount = Member::where('grade_level', '12')->count();
            Member::where('grade_level', '12')->update([
                'grade_level' => 'alumni',
            ]);
            Saver::where('grade_level', '12')->update([
                'grade_level' => 'alumni',
            ]);

            // 2. Grade 11 -> Grade 12
            $eleventhMembers = Member::where('grade_level', '11')->get();
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
            $tenthMembers = Member::where('grade_level', '10')->get();
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
            ];
        });
    }
}
