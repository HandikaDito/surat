<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Disposition;
use App\Models\User;

class DispositionReportFactory extends Factory
{
    public function definition(): array
    {
        $types = ['pdf', 'image', 'video'];
        $type = $this->faker->randomElement($types);

        return [
            'disposition_id' => Disposition::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,

            'keterangan' => $this->faker->sentence(),

            'file_path' => 'reports/dummy.' . match($type) {
                'pdf' => 'pdf',
                'image' => 'jpg',
                'video' => 'mp4'
            },

            'file_type' => $type,
        ];
    }
}