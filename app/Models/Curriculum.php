<?php

namespace App\Models;

use App\Casts\TimeCast;
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
        'start_time' => TimeCast::class,
        'end_time' => TimeCast::class   
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function getSeriesAttribute($value)
    {
        return match ($value) {
            'educacao_infantil' => 'Educação Infantil',
            'fundamental_i' => 'Fundamental I',
            'fundamental_ii' => 'Fundamental II',
            'ensino_medio' => 'Ensino Médio',
            'eja' => 'EJA',
            'tecnico' => 'Técnico',
            'other' => 'Outro',
        };
    }

    public function getModalityAttribute($value)
    {
        return match ($value) {
            'bercario' => 'Berçário',
            'creche' => 'Creche',
            'pre_escola' => 'Pré-Escola',
            'fundamental' => 'Fundamental',
            'medio' => 'Médio',
            'eja' => 'EJA',
            'educacao_especial' => 'Educação Especial',
            'tecnico' => 'Técnico',
            'other' => 'Outro',
        };
    }

    public function getDefaultTimeClassAttribute($value)
    {
        return $value . ' h';
    }

}
