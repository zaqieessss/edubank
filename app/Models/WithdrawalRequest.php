<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'saver_id',
        'amount',
        'pickup_date',
        'reason',
        'status',
        'admin_notes',
        'approved_by',
        'approved_at',
        'transaction_id',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'pickup_date'  => 'date',
        'approved_at'  => 'datetime',
    ];

    /**
     * Nasabah yang mengajukan penarikan
     */
    public function saver(): BelongsTo
    {
        return $this->belongsTo(Saver::class);
    }

    /**
     * Admin/Guru yang memverifikasi pengajuan
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Transaksi pencairan resmi ketika uang fisik diserahkan
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Scope untuk pengajuan yang siap dicairkan hari ini
     */
    public function scopeReadyForPickupToday($query)
    {
        return $query->where('status', 'approved')
                     ->whereDate('pickup_date', now()->toDateString());
    }

    /**
     * Scope untuk pengajuan aktif yang masih pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
