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
        return $this->belongsTo(PeriodSchoolYear::class);
    }

    public function getBimesterAttribute()
    {
        return match ($this->attributes['bimester']) {
            '1' => '1º Bimestre',
            '2' => '2º Bimestre',
            '3' => '3º Bimestre',
            '4' => '4º Bimestre',
        };
    }
}
