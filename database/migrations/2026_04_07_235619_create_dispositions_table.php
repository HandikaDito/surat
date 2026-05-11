<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dispositions', function (Blueprint $table) {
            $table->id();

            // 🔥 relasi ke surat
            $table->foreignId('surat_id')
                ->constrained('surat_masuk')
                ->cascadeOnDelete();

            // 🔥 pengirim WAJIB ADA
            $table->foreignId('from_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('catatan')->nullable();
            $table->date('deadline')->nullable();

            $table->timestamps();

            // 🔥 INDEX WAJIB
            $table->index('surat_id');
            $table->index('from_user_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispositions');
    }
};