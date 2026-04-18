<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class SuratKeluarFactory extends Factory
{
    public function definition(): array
    {
        $user = User::where('role_level', '>=', 3)->inRandomOrder()->first();

        return [
            'nomor_surat' => 'SK-' . $this->faker->unique()->numberBetween(100, 999),
            'tanggal_surat' => $this->faker->date(),

            'perihal' => $this->faker->sentence(),

            'isi_surat' => $this->faker->paragraph(),

            'created_by' => $user ? $user->id : 1,

            'status' => $this->faker->randomElement([
                'draft',
                'review',
                'approved',
                'rejected'
            ]),
        ];
    }
}