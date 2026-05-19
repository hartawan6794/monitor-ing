<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('available_databases', function (Blueprint $table) {
            $table->dropColumn(['package_type', 'expired_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('available_databases', function (Blueprint $table) {
            $table->string('package_type')->nullable();
            $table->date('expired_at')->nullable();
        });
    }
};
