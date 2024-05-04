<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserSchool;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Maike (Admin)',
            'email' => 'test@example.com',
        ]);

        $admin->assignRole('admin');

        $secretaria = User::factory()->create([
            'name' => 'José (Secretaria)',
            'email' => 'secretaria@secretaria.com',
        ]);

        $secretaria->assignRole('secretary');
        UserSchool::create([
            'user_id' => $secretaria->id,
            'school_id' => 1,
            'role_id' => Role::where('name', 'secretary')->first()->id,
        ]);

        $professor = User::factory()->create([
            'name' => 'Fernando (Professor)',
            'email' => 'professor@professor.com',
        ]);
        $professor->assignRole('teacher');
        UserSchool::create([
            'user_id' => $professor->id,
            'school_id' => 1,
            'role_id' => Role::where('name', 'teacher')->first()->id,
        ]);
    }
}
