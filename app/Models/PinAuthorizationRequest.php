<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PinAuthorizationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'saver_id',
        'teller_id',
        'amount',
        'description',
        'token',
        'status',
        'pin_verified_at',
        'expires_at',
    ];

    protected $casts = [
        'amount'          => 'integer',
        'pin_verified_at' => 'datetime',
        'expires_at'      => 'datetime',
    ];

    public function saver(): BelongsTo
    {
        return $this->belongsTo(Saver::class);
    }

    public function teller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teller_id');
    }

    public function isExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending')
            ->where('expires_at', '>', now());
    }
}
