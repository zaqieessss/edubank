<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles Allowed roles (e.g. 'admin', 'teller', 'saver')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        if ($user->status !== 'active') {
            if ($request->expectsJson()) {
                $user->currentAccessToken()?->delete();
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda sedang dinonaktifkan.',
                ], 403);
            }
            Auth::guard('web')->logout();
            return redirect()->route('login')->with('error', 'Akun Anda sedang dinonaktifkan.');
        }

        if (!in_array($user->role, $roles, true)) {
            // Block unauthorized URL tampering (UKK Rubric 3.2)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak: Anda tidak memiliki wewenang untuk membuka modul ini.',
                ], 403);
            }

            // Redirect back to user's authorized home or login
            return redirect()->route('login')->with('error', 'Akses ditolak: Anda tidak memiliki wewenang untuk membuka halaman tersebut.');
        }

        return $next($request);
    }
}
