<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'saver_id',
        'account_number',
        'account_type',
        'balance',
        'minimum_balance',
        'status',
        'opened_date',
        'closed_date',
        'qr_code_path',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'balance'         => 'integer',
            'minimum_balance' => 'integer',
            'opened_date'     => 'date',
            'closed_date'     => 'date',
        ];
    }

    public function saver(): BelongsTo
    {
        return $this->belongsTo(Saver::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class)->orderBy('transaction_date', 'desc');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getFormattedBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->balance, 0, ',', '.');
    }

    public function getMaxWithdrawableAttribute(): int
    {
        return max(0, $this->balance - $this->minimum_balance);
    }
}
