<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    use HasFactory;

    public function getRoleAttribute()
    {
        return match ($this->name) {
            'admin' => 'Administrador',
            'secretary' => 'Secretaria',
            default => $this->name,
        };
    }
}
