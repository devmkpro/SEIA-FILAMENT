<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'series',
        'weekly_hours',
        'total_hours',
        'start_time',
        'end_time',
        'modality',
        'default_time_class',
    ];

    protected $casts = [
        'start_time' => 'time',
        'end_time' => 'time',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

}
