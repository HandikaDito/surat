<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('disposition_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('disposition_id')
                ->constrained('dispositions')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('keterangan')->nullable();

            // 🔥 WAJIB FILE
            $table->string('file_path');

            // 🔥 tipe file (dikontrol di aplikasi)
            $table->string('file_type');

            $table->timestamps();

            // 🔥 INDEX (WAJIB)
            $table->index('disposition_id');
            $table->index('user_id');

            // 🔥 OPTIONAL: cegah double report (aktifkan kalau perlu)
            // $table->unique(['disposition_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disposition_reports');
    }
};