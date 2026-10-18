<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeller(): bool
    {
        return $this->role === 'teller';
    }

    public function isSaver(): bool
    {
        return $this->role === 'saver';
    }

    public function saver(): HasOne
    {
        return $this->hasOne(Saver::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function tellerDailyClosings(): HasMany
    {
        return $this->hasMany(DailyClosing::class, 'teller_id');
    }

    public function approvedDailyClosings(): HasMany
    {
        return $this->hasMany(DailyClosing::class, 'admin_id');
    }
}
