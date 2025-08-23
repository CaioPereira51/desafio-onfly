<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        Permission::create(['name' => 'create pedidos']);
        Permission::create(['name' => 'view own pedidos']);
        Permission::create(['name' => 'view all pedidos']);
        Permission::create(['name' => 'update pedido status']);

        // Create roles
        $userRole = Role::create(['name' => 'user']);
        $adminRole = Role::create(['name' => 'admin']);

        // Assign permissions to user role
        $userRole->givePermissionTo([
            'create pedidos',
            'view own pedidos',
        ]);

        // Assign permissions to admin role
        $adminRole->givePermissionTo([
            'create pedidos',
            'view own pedidos',
            'view all pedidos',
            'update pedido status',
        ]);
    }
}
