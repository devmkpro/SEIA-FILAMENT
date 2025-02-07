<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::createOrFirst(['name' => 'admin', 'guard_name' => 'web']);
        $secretary = Role::createOrFirst(['name' => 'secretary', 'guard_name' => 'web']);
        $teacher = Role::createOrFirst(['name' => 'teacher', 'guard_name' => 'web']);
        
        $array = [
            'view-any User',
            'view User',
            'create User',
            'update User',
            'delete User',
            'restore User',
            'force-delete User',

            'view-any Role',
            'view Role',
            'create Role',
            'update Role',
            'delete Role',
            'restore Role',
            'force-delete Role',

            'view-any Permission',
            'view Permission',
            'create Permission',
            'update Permission',
            'delete Permission',
            'restore Permission',

            'view-any State',
            'view State',
            'create State',
            'update State',
            'delete State',
            'restore State',
            'force-delete State',

            'view-any City',
            'view City',
            'create City',
            'update City',
            'delete City',
            'restore City',
            'force-delete City',

            'view-any School',
            'view School',
            'create School',
            'update School',
            'delete School',
            'restore School',
            'force-delete School',

            'view-any UserSchool',
            'view UserSchool',
            'create UserSchool',
            'update UserSchool',
            'delete UserSchool',
            'restore UserSchool',
            'force-delete UserSchool',

            'view-any SchoolYear',
            'view SchoolYear',
            'create SchoolYear',
            'update SchoolYear',
            'delete SchoolYear',
            'restore SchoolYear',
            'force-delete SchoolYear',

            'select-my School',

            'view-any Period',
            'view Period',
            'create Period',
            'update Period',
            'delete Period',
            'restore Period',
            'force-delete Period',

            'view-any Curriculum',
            'view Curriculum',
            'create Curriculum',
            'update Curriculum',
            'delete Curriculum',
            'restore Curriculum',
            'force-delete Curriculum',

            'view-any Classes',
            'view Classes',
            'create Classes',
            'update Classes',
            'delete Classes',
            'restore Classes',
            'force-delete Classes',
        ];

        foreach ($array as $permission) {
            Permission::createOrFirst(['name' => $permission, 'guard_name' => 'web']);
        }

        $admin->revokePermissionTo(Permission::all());
        $admin->givePermissionTo(Permission::all());

        $secretary->revokePermissionTo(Permission::all());
        $secretary->givePermissionTo([
            'select-my School',
          
            'view Period',
            'create Period',
            'update Period',
            'delete Period',
            'restore Period',
            'force-delete Period',

            'view Curriculum',
            'create Curriculum',
            'update Curriculum',
            'delete Curriculum',
            'restore Curriculum',
            'force-delete Curriculum',

            'view-any Classes',
            'view Classes',
            'create Classes',
            'update Classes',
            'delete Classes',
            'restore Classes',
            'force-delete Classes',
        ]);
    }
}
