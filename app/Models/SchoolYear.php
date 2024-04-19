<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
        'start_date',
        'end_date',
        'school_year',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function getActiveAttribute($value)
    {
        return $value === 'Ativa' ? 'Ativa' : 'Inativa';
    }
}
