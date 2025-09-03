<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::insert([
            [
                'id' => 1,
                'name' => 'Admin',
                'email' => 'agnanpes1@gmail.com',
                'email_verified_at' => '2025-08-20 18:37:49',
                'password' => '$2y$12$6I0Uv8B97UFDZaGWHEuNM.QVPv87lOIx5Tj6GL2oSmH...', // Sudah hash
                'remember_token' => null,
                'created_at' => '2025-08-20 18:37:49',
                'updated_at' => '2025-08-21 16:44:05',
                'role' => 'admin',
            ],
        ]);
    }
}
