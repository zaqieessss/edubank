<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id_number',
        'full_name',
        'grade_level',
        'class_name',
        'major',
        'cohort',
        'gender',
        'email',
        'phone_number',
        'address',
        'registration_status',
    ];

    public function saver(): HasOne
    {
        return $this->hasOne(Saver::class);
    }

    public function isRegistered(): bool
    {
        return $this->registration_status === 'registered';
    }
}
