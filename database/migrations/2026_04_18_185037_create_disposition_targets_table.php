<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('disposition_targets', function (Blueprint $table) {
            $table->id();

            // 🔥 relasi
            $table->foreignId('disposition_id')
                ->constrained('dispositions')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // 🔥 status fleksibel
            $table->string('status')->default('unread');

            $table->timestamps();

            // 🔥 hindari duplikasi
            $table->unique(['disposition_id', 'user_id']);

            // 🔥 INDEX PENTING
            $table->index('user_id');
            $table->index('status');

            // 🔥 SUPER INDEX (PALING PENTING)
            $table->index(['user_id', 'status']);

            // 🔥 OPTIONAL (kalau sering query per disposition + status)
            $table->index(['disposition_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disposition_targets');
    }
};