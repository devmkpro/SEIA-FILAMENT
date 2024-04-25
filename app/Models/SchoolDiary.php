<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolDiary extends Model
{
    use HasFactory;

    protected $fillable = ['active', 'school_id', 'period_school_years_id'];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function periodSchoolYear()
    {
        return $this->belongsTo(PeriodSchoolYear::class, 'period_school_years_id');
    }
}
