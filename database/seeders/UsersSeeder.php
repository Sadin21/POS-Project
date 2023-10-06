<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nip' => '90',
                // 'id' => '1',
                'name' => 'Admin',
                'username' => 'admin',
                'address' => 'Jl. Admin',
                'phone' => '081234567890',
                'password' => bcrypt('admin123'),
                'role_id' => 1,
                'created_at' => now(),
            ],
            [
                'nip' => '91',
                // 'id' => '2',
                'name' => 'Kasir',
                'username' => 'kasir',
                'address' => 'Jl. Kasir',
                'phone' => '081234567891',
                'password' => bcrypt('kasir123'),
                'role_id' => 2,
                'created_at' => now(),
            ]
        ]);
    }
}
