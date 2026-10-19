<?php

namespace App\Http\Controllers\Admin;

use App\Enums\GradeLevel;
use App\Enums\Major;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Services\MemberService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MemberController extends Controller
{
    public function __construct(
        protected MemberService $memberService
    ) {}

    public function index(Request $request)
    {
        $query = Member::query();

        if ($request->filled('major')) {
            $query->where('major', $request->major);
        }

        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        if ($request->filled('registration_status')) {
            $query->where('registration_status', $request->registration_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('student_id_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $members = $query->orderBy('grade_level')->orderBy('class_name')->orderBy('full_name')->paginate(15)->withQueryString();

        $majors = Major::cases();
        $grades = GradeLevel::cases();

        return view('admin.members.index', compact('members', 'majors', 'grades'));
    }

    public function create()
    {
        $majors = Major::cases();
        $grades = GradeLevel::cases();

        return view('admin.members.create', compact('majors', 'grades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id_number' => ['required', 'string', 'max:30', 'unique:members,student_id_number'],
            'full_name'         => ['required', 'string', 'max:100'],
            'grade_level'       => ['required', 'in:10,11,12,alumni'],
            'class_name'        => ['required', 'string', 'max:30'],
            'major'             => ['required', 'string', 'in:' . implode(',', Major::values())],
            'cohort'            => ['required', 'string', 'max:4'],
            'gender'            => ['required', 'in:male,female'],
            'email'             => ['required', 'email', 'max:100', 'unique:members,email'],
            'phone_number'      => ['nullable', 'string', 'max:20'],
            'address'           => ['nullable', 'string'],
        ], [
            'student_id_number.unique' => 'NIS sudah terdaftar untuk siswa lain.',
            'email.unique'             => 'Email sudah terdaftar untuk siswa lain.',
            'major.in'                 => 'Jurusan tidak valid. Pilih dari 8 jurusan resmi SMKN 1 Bantul.',
        ]);

        Member::create($validated);

        return redirect()->route('admin.members.index')->with('success', "Data siswa {$validated['full_name']} berhasil ditambahkan.");
    }

    public function edit(Member $member)
    {
        $majors = Major::cases();
        $grades = GradeLevel::cases();

        return view('admin.members.edit', compact('member', 'majors', 'grades'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'student_id_number' => ['required', 'string', 'max:30', 'unique:members,student_id_number,' . $member->id],
            'full_name'         => ['required', 'string', 'max:100'],
            'grade_level'       => ['required', 'in:10,11,12,alumni'],
            'class_name'        => ['required', 'string', 'max:30'],
            'major'             => ['required', 'string', 'in:' . implode(',', Major::values())],
            'cohort'            => ['required', 'string', 'max:4'],
            'gender'            => ['required', 'in:male,female'],
            'email'             => ['required', 'email', 'max:100', 'unique:members,email,' . $member->id],
            'phone_number'      => ['nullable', 'string', 'max:20'],
            'address'           => ['nullable', 'string'],
        ]);

        $member->update($validated);

        // Also sync saver if already registered
        if ($member->saver) {
            $member->saver->update([
                'student_id_number' => $validated['student_id_number'],
                'full_name'         => $validated['full_name'],
                'grade_level'       => $validated['grade_level'],
                'class_name'        => $validated['class_name'],
                'major'             => $validated['major'],
                'cohort'            => $validated['cohort'],
            ]);
        }

        return redirect()->route('admin.members.index')->with('success', "Data siswa {$member->full_name} berhasil diperbarui.");
    }

    public function destroy(Member $member)
    {
        if ($member->registration_status === 'registered' || $member->saver()->exists()) {
            return back()->with('error', 'Gagal menghapus! Siswa ini sudah memiliki rekening tabungan aktif (ON DELETE RESTRICT).');
        }

        $member->delete();

        return back()->with('success', 'Data siswa berhasil dihapus.');
    }

    public function downloadTemplate(): StreamedResponse
    {
        $csvContent = $this->memberService->generateTemplateCsv();

        return response()->streamDownload(function () use ($csvContent) {
            echo $csvContent;
        }, 'template_siswa_smkn1bantul.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt'],
        ], [
            'csv_file.required' => 'Pilih file CSV terlebih dahulu.',
            'csv_file.mimes'    => 'File harus berformat CSV.',
        ]);

        try {
            $result = $this->memberService->importCsv($request->file('csv_file')->getRealPath());

            $message = "Import selesai! {$result['imported']} siswa berhasil ditambahkan, {$result['skipped']} data dilewati (duplikat/invalid).";

            return back()->with('success', $message);
        } catch (Exception $e) {
            return back()->with('error', 'Gagal import file: ' . $e->getMessage());
        }
    }

    public function promoteGrades()
    {
        try {
            $result = $this->memberService->promoteGrades();

            $message = "Kenaikan kelas massal berhasil diproses! " .
                       "Kelas 10 ➔ 11: {$result['promoted_to_11']} siswa, " .
                       "Kelas 11 ➔ 12: {$result['promoted_to_12']} siswa, " .
                       "Kelas 12 ➔ Alumni: {$result['promoted_to_alumni']} siswa.";

            return back()->with('success', $message);
        } catch (Exception $e) {
            return back()->with('error', 'Gagal memproses kenaikan kelas: ' . $e->getMessage());
        }
    }
}
