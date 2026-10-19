<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyClosing extends Model
{
    use HasFactory;

    protected $fillable = [
        'teller_id',
        'admin_id',
        'closing_date',
        'opening_balance',
        'total_deposits',
        'total_withdrawals',
        'system_balance',
        'physical_cash',
        'difference',
        'status',
        'vault_status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'closing_date' => 'date',
            'opening_balance' => 'integer',
            'total_deposits' => 'integer',
            'total_withdrawals' => 'integer',
            'system_balance' => 'integer',
            'physical_cash' => 'integer',
            'difference' => 'integer',
        ];
    }

    public function teller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teller_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function isBalanced(): bool
    {
        return $this->difference === 0;
    }

    public function getSystemCashAttribute(): int
    {
        return (int) ($this->attributes['system_balance'] ?? 0);
    }
}
