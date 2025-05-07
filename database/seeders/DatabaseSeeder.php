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
            'create Promotional',
            'view Promotional',
            'update Promotional',
            'delete Promotional',
            'delete-any Promotional',
            'restore Promotional',
            'restore-any Promotional',
            'reorder Promotional',
            'replicate Promotional',
            'force-delete Promotional',
            'force-delete-any Promotional',
            'view-any Promotional',

            'create Permission',
            'view Permission',
            'update Permission',
            'delete Permission',
            'delete-any Permission',
            'restore Permission',
            'restore-any Permission',
            'reorder Permission',
            'replicate Permission',
            'force-delete Permission',
            'force-delete-any Permission',
            'view-any Permission',

            'create Role',
            'view Role',
            'update Role',
            'delete Role',
            'delete-any Role',
            'restore Role',
            'restore-any Role',
            'force-delete Role',
            'force-delete-any Role',
            'view-any Role',
            'replicate Role',
            'reorder Role',

            "create ActivityLog",
            "create Hotels",
            "create Members",
            "create RedeemLog",
            "create Transactions",
            "create User",
            "create_token",
            "delete ActivityLog",
            "delete Hotels",
            "delete Members",
            "delete RedeemLog",
            "delete Transactions",
            "delete User",
            "delete_any_token",
            "delete_token",
            "delete-any ActivityLog",
            "delete-any Hotels",
            "delete-any Members",
            "delete-any RedeemLog",
            "delete-any Transactions",
            "delete-any User",
            "force_delete_any_token",
            "force_delete_token",
            "force-delete ActivityLog",
            "force-delete Hotels",
            "force-delete Members",
            "force-delete RedeemLog",
            "force-delete Transactions",
            "force-delete User",
            "force-delete-any ActivityLog",
            "force-delete-any Hotels",
            "force-delete-any Members",
            "force-delete-any RedeemLog",
            "force-delete-any Transactions",
            "force-delete-any User",
            "reorder ActivityLog",
            "reorder Hotels",
            "reorder Members",
            "reorder RedeemLog",
            "reorder Transactions",
            "reorder User",
            "reorder_token",
            "replicate ActivityLog",
            "replicate Hotels",
            "replicate Members",
            "replicate RedeemLog",
            "replicate Transactions",
            "replicate User",
            "replicate_token",
            "restore ActivityLog",
            "restore Hotels",
            "restore Members",
            "restore RedeemLog",
            "restore Transactions",
            "restore User",
            "restore_any_token",
            "restore_token",
            "restore-any ActivityLog",
            "restore-any Hotels",
            "restore-any Members",
            "restore-any RedeemLog",
            "restore-any Transactions",
            "restore-any User",
            "transaction.select.hotel_and_type",
            "update ActivityLog",
            "update Hotels",
            "update Members",
            "update RedeemLog",
            "update Transactions",
            "update User",
            "update_token",
            "view ActivityLog",
            "view Hotels",
            "view Members",
            "view RedeemLog",
            "view Transactions",
            "view User",
            "view_any_token",
            "view_token",
            "view-any ActivityLog",
            "view-any Hotels",
            "view-any Members",
            "view-any RedeemLog",
            "view-any Transactions",
            "view-any User",
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
