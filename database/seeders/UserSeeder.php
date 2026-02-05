<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@intimatebaliwedding.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'telp' => '+62 821 1234 5678',
            'role' => 'admin',
        ]);

        // Create another Admin User
        User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@intimatebaliwedding.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'telp' => '+62 821 8765 4321',
            'role' => 'admin',
        ]);

        // Create Regular User
        User::create([
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('user123'),
            'telp' => '+62 812 3456 7890',
            'role' => 'user',
        ]);

        // Create more sample users
        User::create([
            'name' => 'Jane Smith',
            'username' => 'janesmith',
            'email' => 'jane@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('user123'),
            'telp' => '+62 813 9876 5432',
            'role' => 'user',
        ]);
    }
}