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
}
