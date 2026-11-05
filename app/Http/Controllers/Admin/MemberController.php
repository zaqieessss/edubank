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

        $totalMembers = $query->count();
        $members = $query->orderBy('id', 'desc')->take(20)->get();

        $majors = Major::cases();
        $grades = GradeLevel::cases();

        return view('admin.members.index', compact('members', 'totalMembers', 'majors', 'grades'));
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
        // Siswa yang sudah terdaftar sebagai nasabah atau memiliki riwayat transaksi TIDAK BOLEH dihapus
        if ($member->registration_status === 'registered' || $member->saver()->exists()) {
            $saver = $member->saver;

            // Jika statusnya belum alumni, ubah statusnya menjadi nonaktif
            if ($saver) {
                $saver->update([
                    'status'      => 'inactive',
                    'grade_level' => 'alumni',
                ]);
                if ($saver->user) {
                    $saver->user->update(['status' => 'inactive']);
                }
            }

            $member->update([
                'grade_level' => 'alumni',
            ]);

            return back()->with('info', "Siswa {$member->full_name} memiliki data keuangan/rekening sehingga tidak dihapus permanen, melainkan statusnya dinonaktifkan (Alumni).");
        }

        $member->delete();

        return back()->with('success', 'Data siswa berhasil dihapus.');
    }

    public function downloadTemplate(Request $request): StreamedResponse
    {
        $format = strtolower((string) $request->query('format', 'csv'));

        if ($format === 'excel' || $format === 'xlsx') {
            $xlsxContent = $this->memberService->generateTemplateXlsx();
            return response()->streamDownload(function () use ($xlsxContent) {
                echo $xlsxContent;
            }, 'template_siswa_smkn1bantul.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        }

        $csvContent = $this->memberService->generateTemplateCsv();

        return response()->streamDownload(function () use ($csvContent) {
            echo $csvContent;
        }, 'template_siswa_smkn1bantul.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function import(Request $request)
    {
        $file = $request->file('import_file') ?? $request->file('csv_file') ?? $request->file('file');

        if (!$file || !$file->isValid()) {
            return back()->with('error', 'Silakan pilih berkas Excel (.xlsx, .xls) atau CSV (.csv) terlebih dahulu.');
        }

        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['csv', 'txt', 'xlsx', 'xls'], true)) {
            return back()->with('error', 'Format berkas tidak didukung. Harap unggah berkas Excel (.xlsx, .xls) atau CSV (.csv).');
        }

        if ($file->getSize() > 10 * 1024 * 1024) {
            return back()->with('error', 'Ukuran berkas maksimal 10 MB.');
        }

        try {
            $result = $this->memberService->importFile($file->getRealPath());

            $message = "Import selesai! {$result['imported']} siswa berhasil ditambahkan, {$result['skipped']} data dilewati (duplikat/invalid).";

            return back()->with('success', $message);
        } catch (Exception $e) {
            return back()->with('error', 'Gagal import file: ' . $e->getMessage());
        }
    }

    public function searchActiveMembers(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $members = Member::query()
            ->where('grade_level', '!=', 'alumni')
            ->where(function ($query) use ($q) {
                $query->where('full_name', 'like', "%{$q}%")
                      ->orWhere('student_id_number', 'like', "%{$q}%")
                      ->orWhere('class_name', 'like', "%{$q}%");
            })
            ->orderBy('class_name')
            ->orderBy('full_name')
            ->limit(15)
            ->get(['id', 'full_name', 'student_id_number', 'class_name', 'grade_level', 'major']);

        return response()->json($members);
    }

    public function promoteGrades(Request $request)
    {
        $request->validate([
            'excluded_member_ids' => ['nullable', 'array'],
            'excluded_member_ids.*' => ['integer', 'exists:members,id'],
        ]);

        $excludedMemberIds = $request->input('excluded_member_ids', []);

        try {
            $result = $this->memberService->promoteGrades($excludedMemberIds);

            $excludedInfo = !empty($result['excluded_count']) && $result['excluded_count'] > 0
                ? " ({$result['excluded_count']} siswa tinggal kelas/dikecualikan)"
                : "";

            $message = "Kenaikan kelas massal berhasil diproses! " .
                       "Kelas 10 ke 11: {$result['promoted_to_11']} siswa, " .
                       "Kelas 11 ke 12: {$result['promoted_to_12']} siswa, " .
                       "Kelas 12 ke Alumni: {$result['promoted_to_alumni']} siswa." .
                       $excludedInfo;

            return back()->with('success', $message);
        } catch (Exception $e) {
            return back()->with('error', 'Gagal memproses kenaikan kelas: ' . $e->getMessage());
        }
    }
}
