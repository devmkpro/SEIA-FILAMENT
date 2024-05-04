<?php

namespace App\Models;

use App\HasCodeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory, HasCodeTrait;

    protected $fillable = [
        'code',
        'active',
        'school_id',
        'period_school_years_id',
        'curriculum_id',
        'name',
        'modality',
        'turn',
        'max_students',
        'teacher_responsible_id',
    ];

    /**
     * Generate code for the class
     */
    public function generateCode(): string
    {
        return $this->code = 'Turma-' . (Classes::max('id') + 1);
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function periodSchoolYears()
    {
        return $this->belongsTo(PeriodSchoolYear::class, 'period_school_years_id');
    }

    public function curricula()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }

    public function teacherResponsible()
    {
        return $this->belongsTo(User::class, 'teacher_responsible_id');
    }

}
