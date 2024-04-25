<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodSchoolYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
        'school_year_id',
        'type',
        'school_id'
    ];

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function bimesters()
    {
        return $this->hasMany(PeriodBimonthly::class, 'period_school_years_id');
    }

    public function semesters()
    {
        return $this->hasMany(PeriodSemester::class,   'period_school_years_id');
    }

    public function diaries()
    {
        return $this->hasMany(SchoolDiary::class, 'period_school_years_id', 'id');
    }
}
