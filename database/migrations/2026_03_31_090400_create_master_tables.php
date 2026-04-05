<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel Server yang Diizinkan
        Schema::create('authorized_servers', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('server_name');
            $table->string('username');
            $table->string('password');
            $table->string('port')->default('3306');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel Daftar Database
        Schema::create('available_databases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained('authorized_servers')->onDelete('cascade');
            $table->string('db_name');
            $table->string('description')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('available_databases');
        Schema::dropIfExists('authorized_servers');
    }
};
