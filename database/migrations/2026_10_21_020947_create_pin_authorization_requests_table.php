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
        Schema::create('pin_authorization_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saver_id')->constrained('savers')->cascadeOnDelete();
            $table->foreignId('teller_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->string('description')->nullable();
            $table->string('token', 64)->unique();
            $table->enum('status', ['pending', 'approved', 'rejected', 'expired', 'completed'])->default('pending');
            $table->timestamp('pin_verified_at')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['saver_id', 'status']);
            $table->index(['token', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pin_authorization_requests');
    }
};
