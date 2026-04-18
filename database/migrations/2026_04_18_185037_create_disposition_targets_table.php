<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('disposition_targets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('disposition_id')->constrained('dispositions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->enum('status', ['unread','on_progress','done'])->default('unread');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disposition_targets');
    }
};