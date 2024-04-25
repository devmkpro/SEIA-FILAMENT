<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterSchoolDiary extends Model
{
    use HasFactory;

    protected $table = 'semester_school_diaries';

    protected $fillable = [
        'school_diary_id',
        'period_semesters_id',
    ];

    public function schoolDiary()
    {
        return $this->belongsTo(SchoolDiary::class, 'school_diary_id');
    }

    public function semester()
    {
        return $this->belongsTo(PeriodSemester::class, 'period_semesters_id');
    }
    
}
