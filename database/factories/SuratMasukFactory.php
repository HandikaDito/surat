<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SuratMasuk;
use App\Models\User;

class SuratMasukFactory extends Factory
{
    protected $model = SuratMasuk::class;

    public function definition(): array
    {
        // 🔥 HANYA ADMIN (Level 0)
        $admin = User::where('role_level', 0)->first() 
            ?? User::factory()->create([
                'role_level' => 0,
                'jabatan' => 'Admin Sekretariat'
            ]);

        return [
            'nomor_surat' => 'SM-' . $this->faker->unique()->numberBetween(100, 999),

            'tanggal_surat' => $this->faker->date(),
            'tanggal_masuk' => now(),

            'pengirim' => $this->faker->company(),
            'perihal' => $this->faker->sentence(),

            'sifat' => $this->faker->randomElement(['biasa', 'penting', 'rahasia']),

            // 🔄 update field
            'file_path' => 'surat/dummy.pdf',

            // 🔥 workflow field
            'status' => 'baru',
            'is_disposisi' => false,
            'deadline' => $this->faker->optional()->date(),

            // 🔐 wajib admin
            'created_by' => $admin->id,
        ];
    }
}