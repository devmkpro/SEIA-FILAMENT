<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSchool extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
        'role_id',
        'school_id',
        'user_id',
    ];


    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
