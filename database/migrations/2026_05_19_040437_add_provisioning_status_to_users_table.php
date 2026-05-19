<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 'unregistered' → belum berlangganan
            // 'pending'      → sudah bayar, belum ada DB terhubung (perlu provisioning admin)
            // 'provisioned'  → sudah bayar + minimal 1 DB terhubung (siap pakai)
            $table->enum('provisioning_status', ['unregistered', 'pending', 'provisioned'])
                  ->default('unregistered')
                  ->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('provisioning_status');
        });
    }
};
