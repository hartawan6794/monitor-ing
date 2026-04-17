<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('database_access_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('available_database_id')->constrained('available_databases')->onDelete('cascade');
            $table->string('access_key', 64)->unique(); // UUID single-use key
            $table->timestamp('expires_at');            // Berlaku 5 menit
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('database_access_keys');
    }
};
