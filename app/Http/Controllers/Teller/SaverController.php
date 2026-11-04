<?php

namespace App\Http\Controllers\Teller;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Saver;
use App\Services\AccountService;
use App\Services\QrCodeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaverController extends Controller
{
    public function __construct(
        protected AccountService $accountService,
        protected QrCodeService $qrCodeService
    ) {}

    public function index(Request $request)
    {
        $query = Saver::with(['member', 'user', 'account']);

        if ($request->filled('major')) {
            $query->where('major', $request->major);
        }

        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%")
                  ->orWhere('student_id_number', 'like', "%{$search}%");
            });
        }

        $savers = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('teller.savers.index', compact('savers'));
    }

    public function create()
    {
        $unregisteredMembers = Member::where('registration_status', 'unregistered')
            ->orderBy('grade_level')
            ->orderBy('class_name')
            ->orderBy('full_name')
            ->get();

        return view('teller.savers.create', compact('unregisteredMembers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id'       => ['required', 'exists:members,id'],
            'initial_deposit' => ['required', 'integer', 'min:10000'],
            'security_pin'    => ['required', 'digits:6'],
            'password'        => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'initial_deposit.min' => 'Setoran awal buka rekening minimal Rp 10.000.',
            'security_pin.digits' => 'PIN keamanan harus berupa 6 digit angka.',
            'password.required'   => 'Kata sandi akun login siswa wajib diisi.',
            'password.min'        => 'Kata sandi akun login minimal 6 karakter.',
            'password.confirmed'  => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $member = Member::findOrFail($validated['member_id']);

        try {
            $saver = $this->accountService->openAccount(
                member: $member,
                initialDeposit: (int) $validated['initial_deposit'],
                pin: $validated['security_pin'],
                processedByUserId: Auth::id(),
                password: $validated['password']
            );

            return redirect()->route('teller.savers.show', $saver)
                ->with('success', "Rekening tabungan {$saver->full_name} berhasil dibuka dengan Nomor Rekening: {$saver->account_number}.");
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Saver $saver)
    {
        $saver->load(['member', 'user', 'account']);
        $transactions = $saver->transactions()->with('user')->paginate(10)->withQueryString();

        // Generate data URI for digital card display and printing
        $qrCodeDataUri = $this->qrCodeService->generateDataUri($saver->account_number);

        return view('teller.savers.show', compact('saver', 'transactions', 'qrCodeDataUri'));
    }
}
