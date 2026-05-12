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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pricing_plan_id')->nullable()->constrained('pricing_plans')->onDelete('set null');
            $table->date('starts_at');
            $table->date('expires_at')->index(); // Index untuk query harian
            $table->enum('status', ['active', 'expired', 'cancelled', 'pending'])->default('active');
            $table->datetime('last_reminded_at')->nullable();
            $table->unsignedTinyInteger('remind_count')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
