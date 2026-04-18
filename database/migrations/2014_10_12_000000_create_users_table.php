<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // RBAC (0 - 5)
            $table->unsignedTinyInteger('role_level')->default(5);

            // Struktur organisasi
            $table->foreignId('parent_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('jabatan')->nullable();
            $table->string('unit')->nullable();

            $table->string('no_hp')->nullable();
            $table->boolean('is_active')->default(true);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};