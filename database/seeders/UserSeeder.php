<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Level 0 - Admin
        $admin = User::create([
            'name' => 'Admin Sekretariat',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'role_level' => 0,
            'jabatan' => 'Admin Sekretariat',
        ]);

        // Level 1 - Dirut
        $dirut = User::create([
            'name' => 'Sunanto, S. T., M. M.',
            'email' => 'dirut@mail.com',
            'password' => Hash::make('password'),
            'role_level' => 1,
            'jabatan' => 'Direktur Utama',
        ]);

        // Level 2 - Direktur
        $direktur = User::create([
            'name' => 'Kuntofiq, S. T.',
            'email' => 'dirtek@mail.com',
            'password' => Hash::make('password'),
            'role_level' => 2,
            'jabatan' => 'Direktur Teknik',
            'parent_id' => $dirut->id
        ]);

        // Level 3 - Kabag
        $kabag = User::create([
            'name' => 'Nourma Wiji Lestari, S. Si.',
            'email' => 'kabagproduksi@mail.com',
            'password' => Hash::make('password'),
            'role_level' => 3,
            'jabatan' => 'Kabag Produksi',
            'parent_id' => $direktur->id
        ]);

        // Level 4 - Kasubbag
        $kasubbag = User::create([
            'name' => 'Jefri Setiawan, S. Kom.',
            'email' => 'kasubbagperawatan@mail.com',
            'password' => Hash::make('password'),
            'role_level' => 4,
            'jabatan' => 'Kasubbag Perawatan',
            'parent_id' => $kabag->id
        ]);

        // Level 5 - Staff
        User::create([
            'name' => 'Ibnu Navis',
            'email' => 'staffperawatan1@mail.com',
            'password' => Hash::make('password'),
            'role_level' => 5,
            'jabatan' => 'Staf Perawatan',
            'parent_id' => $kasubbag->id
        ]);
    }
}