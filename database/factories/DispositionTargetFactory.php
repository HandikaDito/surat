<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Disposition;
use App\Models\User;

class DispositionTargetFactory extends Factory
{
    public function definition(): array
    {
        $disposition = Disposition::inRandomOrder()->first();

        // ambil target (level lebih bawah)
        $targetUser = User::where('role_level', '>=', 3)
            ->inRandomOrder()
            ->first();

        return [
            'disposition_id' => $disposition->id,
            'user_id' => $targetUser->id,

            'status' => $this->faker->randomElement([
                'unread',
                'on_progress',
                'done'
            ]),
        ];
    }
}