<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\SuratMasuk;

class DispositionFactory extends Factory
{
    public function definition(): array
    {
        // ambil pengirim (bukan admin)
        $fromUser = User::where('role_level', '>', 0)
            ->inRandomOrder()
            ->first() ?? User::factory()->create();

        // pastikan ada surat
        $surat = SuratMasuk::inRandomOrder()->first() 
            ?? SuratMasuk::factory()->create();

        return [
            'surat_id' => $surat->id,
            'from_user_id' => $fromUser->id,

            'catatan' => $this->faker->randomElement([
                'Segera ditindaklanjuti',
                'Koordinasikan dengan tim',
                'Cek lapangan dan laporkan',
                'Selesaikan secepatnya',
                'Lakukan perbaikan segera'
            ]),

            'deadline' => $this->faker->optional()->dateTimeBetween('now', '+7 days'),
        ];
    }
}