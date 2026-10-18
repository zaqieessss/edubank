<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Saver extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'user_id',
        'account_number',
        'student_id_number',
        'full_name',
        'grade_level',
        'class_name',
        'major',
        'cohort',
        'security_pin',
        'qr_code_path',
        'joined_date',
        'status',
    ];

    protected $hidden = [
        'security_pin',
    ];

    protected function casts(): array
    {
        return [
            'joined_date' => 'date',
        ];
    }

    public function getBalanceAttribute(): int
    {
        return $this->account?->balance ?? 0;
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Account::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class)->orderBy('transaction_date', 'desc');
    }
}
