<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Disposition;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        $disposition = Disposition::inRandomOrder()->first();

        return [
            'user_id' => $user ? $user->id : 1,
            'disposition_id' => $disposition ? $disposition->id : null,

            'title' => $this->faker->randomElement([
                'Disposisi Baru',
                'Tugas Baru',
                'Laporan Selesai'
            ]),

            'message' => $this->faker->sentence(),

            'url' => $disposition 
                ? '/disposisi/' . $disposition->id 
                : '/dashboard',

            'is_read' => $this->faker->boolean(30),
            'read_at' => null,
        ];
    }
}