<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('configs')->insert([
            
            // 🏢 IDENTITAS APLIKASI
            [
                'key' => 'app_name',
                'value' => 'E-Disposisi',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'key' => 'company_name',
                'value' => 'PDAM Tirto Panguripan',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 📧 EMAIL SYSTEM
            [
                'key' => 'system_email',
                'value' => 'pdepamkdl@yahoo.co.id',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ⏱️ DEFAULT DEADLINE (hari)
            [
                'key' => 'default_deadline_days',
                'value' => '3',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🔔 NOTIFIKASI
            [
                'key' => 'enable_email_notification',
                'value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'key' => 'enable_whatsapp_notification',
                'value' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🎨 TEMA
            [
                'key' => 'theme_color',
                'value' => 'blue',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}