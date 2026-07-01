<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrador General',
            'email' => 'admin@upea.bo',
            'password' => Hash::make('admin123'),
            'role' => 'administrador',
            'is_active' => true,
        ]);
    }
}
