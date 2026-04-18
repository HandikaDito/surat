<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratKeluar;

class SuratKeluarSeeder extends Seeder
{
    public function run(): void
    {
        SuratKeluar::factory(10)->create();
    }
}