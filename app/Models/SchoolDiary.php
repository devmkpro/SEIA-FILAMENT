<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolDiary extends Model
{
    use HasFactory;

    protected $fillable = ['active', 'school_id'];


    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }


    public function bimester()
    {
        return $this->belongsToMany(PeriodBimonthly::class, 'bimester_school_diaries', 'school_diary_id', 'period_bimonthlies_id');
    }

    public function semester()
    {
        return $this->belongsToMany(PeriodSemester::class, 'semester_school_diaries', 'school_diary_id', 'period_semesters_id');
    }


}
