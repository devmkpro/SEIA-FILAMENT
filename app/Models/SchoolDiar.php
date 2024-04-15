<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolDiar extends Model
{
    use HasFactory;

    protected $table = 'school_diares';

    protected $fillable = [
        'active',
        'school_id',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
}
