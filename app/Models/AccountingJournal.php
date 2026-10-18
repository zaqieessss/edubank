<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountingJournal extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'account_code',
        'account_name',
        'position',
        'amount',
        'entry_date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'entry_date' => 'datetime',
        ];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
