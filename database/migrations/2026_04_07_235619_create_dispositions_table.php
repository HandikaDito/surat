<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dispositions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('surat_id')->constrained('surat_masuk')->cascadeOnDelete();

            $table->foreignId('from_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->text('catatan')->nullable();
            $table->date('deadline')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispositions');
    }
};