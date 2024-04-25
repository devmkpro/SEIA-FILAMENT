<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimesterSchoolDiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_diary_id',
        'period_bimonthlies_id',
    ];

    public function schoolDiary()
    {
        return $this->belongsTo(SchoolDiary::class, 'school_diary_id');
    }

    public function bimester()
    {
        return $this->belongsTo(PeriodBimonthly::class, 'period_bimonthlies_id');
    }
}
