<?php

namespace App\Models;

use App\HasCodeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory, HasCodeTrait;

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
