<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PinAuthorizationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PinAuthorizationController extends Controller
{
    /**
     * Check if there is an active pending PIN authorization request for this saver.
     */
    public function checkPending(Request $request)
    {
        $user = Auth::user();
        $saver = $user->saver;

        if (!$saver) {
            return response()->json([
                'success' => false,
                'message' => 'Data rekening nasabah tidak ditemukan.',
            ], 404);
        }

        // Clean up any stale expired or older pending requests
        PinAuthorizationRequest::where('saver_id', $saver->id)
            ->where('status', 'pending')
            ->where('expires_at', '<=', now())
            ->update(['status' => 'expired']);

        // Ambil HANYA permintaan pending paling terbaru yang belum kedaluwarsa
        $pending = PinAuthorizationRequest::with('teller')
            ->where('saver_id', $saver->id)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->orderBy('id', 'desc')
            ->first();

        if ($pending) {
            // Batalkan semua pending lain yang lebih lama dari permintaan terbaru ini
            PinAuthorizationRequest::where('saver_id', $saver->id)
                ->where('status', 'pending')
                ->where('id', '<', $pending->id)
                ->update(['status' => 'expired']);
        }

        if (!$pending) {
            return response()->json([
                'success'     => true,
                'has_pending' => false,
                'data'        => null,
            ]);
        }

        return response()->json([
            'success'     => true,
            'has_pending' => true,
            'data'        => [
                'id'                 => $pending->id,
                'amount'             => $pending->amount,
                'amount_formatted'   => 'Rp ' . number_format($pending->amount, 0, ',', '.'),
                'teller_name'        => $pending->teller?->name ?? 'Petugas Teller',
                'description'        => $pending->description ?? 'Penarikan Tunai di Loket',
                'expires_in_seconds' => max(0, (int) now()->diffInSeconds($pending->expires_at, false)),
                'created_at'         => $pending->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Customer authorizes the withdrawal by entering their 6-digit PIN on mobile.
     */
    public function verify(Request $request, $id)
    {
        $request->validate([
            'pin' => ['required', 'digits:6'],
        ], [
            'pin.required' => 'PIN keamanan wajib diisi.',
            'pin.digits'   => 'PIN harus berupa 6 digit angka.',
        ]);

        $user = Auth::user();
        $saver = $user->saver;

        if (!$saver) {
            return response()->json([
                'success' => false,
                'message' => 'Data rekening nasabah tidak ditemukan.',
            ], 404);
        }

        $authRequest = PinAuthorizationRequest::where('id', $id)
            ->where('saver_id', $saver->id)
            ->first();

        if (!$authRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan otorisasi tidak ditemukan.',
            ], 404);
        }

        if ($authRequest->status !== 'pending' || $authRequest->isExpired()) {
            if ($authRequest->status === 'pending') {
                $authRequest->update(['status' => 'expired']);
            }
            return response()->json([
                'success' => false,
                'message' => 'Permintaan otorisasi telah kedaluwarsa atau tidak aktif.',
            ], 422);
        }

        // Verify PIN against saver's security_pin
        if (!Hash::check($request->pin, $saver->security_pin)) {
            return response()->json([
                'success' => false,
                'message' => 'PIN keamanan yang Anda masukkan salah. Silakan coba lagi.',
            ], 422);
        }

        // Approve
        $authRequest->update([
            'status'          => 'approved',
            'pin_verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Otorisasi penarikan berhasil disetujui! Teller akan segera menyerahkan uang tunai.',
        ]);
    }

    /**
     * Customer rejects the withdrawal request from mobile.
     */
    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        $saver = $user->saver;

        if (!$saver) {
            return response()->json(['success' => false, 'message' => 'Data nasabah tidak ditemukan.'], 404);
        }

        $authRequest = PinAuthorizationRequest::where('id', $id)
            ->where('saver_id', $saver->id)
            ->first();

        if ($authRequest && $authRequest->status === 'pending') {
            $authRequest->update(['status' => 'rejected']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Permintaan penarikan telah ditolak.',
        ]);
    }
}
