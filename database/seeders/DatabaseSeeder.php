<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Daftar permission yang hanya berkaitan dengan Role dan Permission
        $permissions = [
            'create Permission',
            'view Permission',
            'update Permission',
            'delete Permission',
            'restore Permission',
            'force-delete Permission',
            'view-any Permission',

            'create Role',
            'view Role',
            'update Role',
            'delete Role',
            'restore Role',
            'force-delete Role',
            'view-any Role',
        ];

        // Tambahkan permissions ke database
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
        }

        // Buat role Super Admin dan berikan semua permission di atas
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdminRole->syncPermissions($permissions);

        $user = User::factory()->create([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt("admin"),
        ]);

        $user->assignRole($superAdminRole);
    }
}