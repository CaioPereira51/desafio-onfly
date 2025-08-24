<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make(env('ADMIN_PASSWORD', 'Admin@123')),
        ]);
        $admin->assignRole('admin');

        // Create regular user
        $user = User::create([
            'name' => 'Usuário Comum',
            'email' => 'user@example.com',
            'password' => Hash::make(env('USER_PASSWORD', 'User@123')),
        ]);
        $user->assignRole('user');

        // Create additional test users
        $users = [
            [
                'name' => 'João Silva',
                'email' => 'joao@example.com',
                'password' => Hash::make(env('TEST_USER_PASSWORD', 'Test@123')),
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria@example.com',
                'password' => Hash::make(env('TEST_USER_PASSWORD', 'Test@123')),
            ],
            [
                'name' => 'Pedro Costa',
                'email' => 'pedro@example.com',
                'password' => Hash::make(env('TEST_USER_PASSWORD', 'Test@123')),
            ],
        ];

        foreach ($users as $userData) {
            $newUser = User::create($userData);
            $newUser->assignRole('user');
        }
    }
}
