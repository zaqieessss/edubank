<?php

namespace App\Http\Middleware;

use App\Models\DailyClosing;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckDailyClosingStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Hanya berlaku untuk teller
        if ($user && $user->role === 'teller') {
            $today = now()->toDateString();

            // Cek apakah teller sudah menutup laporan kas hari ini (status submitted, approved, atau discrepancy)
            $existingClosing = DailyClosing::where('teller_id', $user->id)
                ->whereDate('closing_date', $today)
                ->whereIn('status', ['submitted', 'approved', 'discrepancy'])
                ->first();

            if ($existingClosing) {
                $message = 'Buku kas loket hari ini telah ditutup (Daily Closing). Tidak diperkenankan melakukan transaksi setoran atau penarikan tunai lagi sampai hari kerja berikutnya.';

                // Jika request AJAX / JSON (seperti scanner auto-lookup atau request-pin)
                if ($request->expectsJson() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'closed'  => true,
                        'message' => $message,
                    ], 403);
                }

                // Jika request halaman biasa (GET scanner / create transaksi), redirect ke dashboard loket dengan modal alert
                return redirect()->route('teller.dashboard')->with('daily_closed_error', $message);
            }
        }

        return $next($request);
    }
}
