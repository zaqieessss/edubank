<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Riwayat mutasi rekening dengan filter & pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $saver = $request->user()->saver;

        if (!$saver) {
            return response()->json(['success' => false, 'message' => 'Nasabah tidak ditemukan.'], 404);
        }

        $query = $saver->transactions()->with('user:id,name');

        // Filter tipe: 'deposit' atau 'withdraw' / 'withdrawal'
        if ($request->filled('type')) {
            $filterType = strtolower($request->type);
            if ($filterType === 'deposit') {
                $query->where('transaction_type', 'deposit');
            } elseif ($filterType === 'withdraw' || $filterType === 'withdrawal') {
                $query->whereIn('transaction_type', ['withdraw', 'withdrawal']);
            }
        }

        // Filter rentang tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($request->input('per_page', 15));

        $formattedData = collect($transactions->items())->map(function ($trx) {
            return [
                'id'               => $trx->id,
                'transaction_code' => $trx->transaction_code,
                'type'             => ($trx->transaction_type === 'withdrawal') ? 'withdraw' : $trx->transaction_type,
                'amount'           => (int) $trx->amount,
                'balance_before'   => (int) $trx->balance_before,
                'balance_after'    => (int) $trx->balance_after,
                'description'      => $trx->description,
                'teller_name'      => $trx->user->name ?? 'Petugas Teller',
                'date'             => $trx->transaction_date ? $trx->transaction_date->format('Y-m-d H:i:s') : null,
                'formatted_date'   => $trx->transaction_date ? $trx->transaction_date->translatedFormat('d F Y, H:i') : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $formattedData,
            'meta'    => [
                'current_page' => $transactions->currentPage(),
                'last_page'    => $transactions->lastPage(),
                'per_page'     => $transactions->perPage(),
                'total'        => $transactions->total(),
            ],
        ]);
    }

    /**
     * Detail struk transaksi.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $saver = $request->user()->saver;

        $trx = $saver->transactions()->with('user:id,name')->find($id);

        if (!$trx) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id'               => $trx->id,
                'transaction_code' => $trx->transaction_code,
                'type'             => ($trx->transaction_type === 'withdrawal') ? 'withdraw' : $trx->transaction_type,
                'amount'           => (int) $trx->amount,
                'balance_before'   => (int) $trx->balance_before,
                'balance_after'    => (int) $trx->balance_after,
                'description'      => $trx->description,
                'teller_name'      => $trx->user->name ?? 'Petugas Teller',
                'date'             => $trx->transaction_date ? $trx->transaction_date->translatedFormat('l, d F Y H:i:s') : null,
            ],
        ]);
    }
}
