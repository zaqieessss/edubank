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
        Schema::create('daily_closings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teller_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->date('closing_date');
            $table->bigInteger('opening_balance')->default(0);
            $table->bigInteger('total_deposits')->default(0);
            $table->bigInteger('total_withdrawals')->default(0);
            $table->bigInteger('system_balance')->default(0); // opening + deposits - withdrawals
            $table->bigInteger('physical_cash')->default(0); // Input fisik uang teller
            $table->bigInteger('difference')->default(0); // physical_cash - system_balance
            $table->enum('status', ['draft', 'submitted', 'approved', 'discrepancy'])->default('draft');
            $table->enum('vault_status', ['with_teller', 'transferred_to_vault'])->default('with_teller');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_closings');
    }
};
