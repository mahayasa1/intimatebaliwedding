<?php

namespace Database\Seeders;

use App\Models\User;
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
            'email' => 'intimatebaliwedding@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'telp' => '+62 821 1234 5678',
            'role' => 'admin',
        ]);
    }
}