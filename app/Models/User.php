<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole([
            'admin',
            'secretary',
        ]);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function schools(): mixed
    {
        if ($this->isAdmin()) {
            return School::all();
        }

        return $this->belongsToMany(School::class, 'user_schools', 'user_id', 'school_id')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function hasRoleForSchool($role, $schoolCode)
    {
        $role = Role::where('name', $role)->first();
        return $this->schools()->where('code', $schoolCode)->wherePivot('role_id', $role->id)->exists();
    }

    public function hasPermissionForSchool(string $permission, string $schoolCode): bool
    {

        $school = School::where('code', $schoolCode)->first();

        if ($this->hasRole('admin') && $school) {
            return true;
        }

        if (!$school || !$this->schools()->where('school_id', $school->id)->exists()) {
            return false;
        }

        $roleID = $this->schools()->where('code', $schoolCode)->first()->pivot->role_id;
        $role = Role::find($roleID);

        if (!$this->hasRoleForSchool($role->name, $schoolCode)) {
            return false;
        }

        return $this->hasPermission($permission, $role);
    }

    public function hasPermission(string $permission, Role $role): bool
    {
        $permissions = $role->permissions;
        if ($permissions->contains('name', $permission) || $this->hasRole('admin')) {
            return true;
        }
        return false;
    }
}
