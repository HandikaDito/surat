<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Disposition;
use App\Models\DispositionTarget;
use App\Models\DispositionReport;

class DispositionSeeder extends Seeder
{
    public function run(): void
    {
        Disposition::factory()
            ->count(5)
            ->create()
            ->each(function ($d) {

                DispositionTarget::factory()
                    ->count(rand(1,3))
                    ->create([
                        'disposition_id' => $d->id
                    ]);

                DispositionReport::factory()
                    ->count(rand(0,2))
                    ->create([
                        'disposition_id' => $d->id
                    ]);
            });
    }
}