<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();

            // 🔥 nomor unik
            $table->string('nomor_surat')->unique();

            $table->date('tanggal_surat');

            $table->string('tujuan');

            $table->string('perihal');
            $table->text('isi_surat')->nullable();

            // 🔥 FILE SURAT (PDF)
            $table->string('file_path')->nullable();

            // 🔥 CREATOR
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            // 🔥 APPROVAL
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            // 🔥 STATUS
            $table->enum('status', ['draft', 'review', 'approved', 'rejected'])
                ->default('draft');

            $table->timestamps();

            // ================= INDEX =================

            $table->index('status');
            $table->index('tanggal_surat');
            $table->index('created_by');
            $table->index('tujuan');

            // 🔥 COMPOSITE (penting untuk dashboard & filter)
            $table->index(['status', 'tanggal_surat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluar');
    }
};