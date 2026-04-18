<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();

            $table->string('nomor_surat');
            $table->date('tanggal_surat');
            $table->date('tanggal_masuk');
            $table->string('pengirim');
            $table->string('perihal');
            $table->string('sifat')->nullable();

            $table->string('file_path')->nullable();

            $table->enum('status', ['baru','diproses','selesai'])->default('baru');
            $table->boolean('is_disposisi')->default(false);

            $table->date('deadline')->nullable();

            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuk');
    }
};