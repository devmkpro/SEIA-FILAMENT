<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodSemester extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
        'period_school_years_id',
        'start_date',
        'end_date',
        'semester',
    ];

    public function periodSchoolYear()
    {
        return $this->belongsTo(PeriodSchoolYear::class, 'period_school_years_id');
    }
}
