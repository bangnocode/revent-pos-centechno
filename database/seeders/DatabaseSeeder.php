<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Utama',
                'username' => 'admin',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
            ]
        );

        // Create Kasir
        User::updateOrCreate(
            ['email' => 'kasir@gmail.com'],
            [
                'name' => 'Kasir Toko',
                'username' => 'kasir',
                'password' => bcrypt('kasir123'),
                'role' => 'kasir',
            ]
        );

        // Seed Operators to satisfy foreign key constraints
        // Admin Operator
        \App\Models\Operator::updateOrCreate(
            ['id_operator' => 'admin'],
            [
                'nama_lengkap' => 'Admin Utama',
                'username' => 'admin',
                'password_hash' => bcrypt('admin123'),
                'level_akses' => 'admin',
                'status_aktif' => true,
            ]
        );

        // Kasir Operator
        \App\Models\Operator::updateOrCreate(
            ['id_operator' => 'kasir'],
            [
                'nama_lengkap' => 'Kasir Toko',
                'username' => 'kasir',
                'password_hash' => bcrypt('kasir123'),
                'level_akses' => 'kasir',
                'status_aktif' => true,
            ]
        );
    }
}
