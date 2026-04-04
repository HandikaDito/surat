<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Config::insert([
            [
                'code' => 'default_password',
                'value' => 'pdamkendal',
            ],
            [
                'code' => 'page_size',
                'value' => '5',
            ],
            [
                'code' => 'app_name',
                'value' => 'Aplikasi Surat Menyurat',
            ],
            [
                'code' => 'institution_name',
                'value' => 'PDAM Tirto Panguripan Kendal',
            ],
            [
                'code' => 'institution_address',
                'value' => 'Jl. Pemuda No. 62 Kendal',
            ],
            [
                'code' => 'institution_phone',
                'value' => '08122578484',
            ],
            [
                'code' => 'institution_email',
                'value' => 'pdepamkdl@yahoo.co.id',
            ],
            [
                'code' => 'language',
                'value' => 'id',
            ],
            [
                'code' => 'pic',
                'value' => 'Handika Dito Aulia Baihaqi',
            ],
        ]);
    }
}
