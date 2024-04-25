<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodBimonthly extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
        'period_school_years_id',
        'start_date',
        'end_date',
        'bimester',
    ];

    public function periodSchoolYear()
    {
        return $this->belongsTo(PeriodSchoolYear::class, 'period_school_years_id');
    }

    public function bimesterDiary()
    {
        return $this->hasOne(BimesterSchoolDiary::class, 'period_bimonthlies_id');
    }
}
