<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'active',
        'type',
        'category',
        'name',
        'email',
        'address',
        'zip_code',
        'phone',
        'neighborhood',
        'landline',
        'cnpj',
        'complement',
        'acronym',
        'city_id',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($school) {
            $school->code = $school->generateCode();
        });
    }

    /**
     * Generate code for the school
     */
    public function generateCode(): string
    {
        return $this->code = 'SEIA-' . (School::max('id') + 1);
    }

    /**
     * Users
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_schools', 'school_id', 'user_id');
    }
}
