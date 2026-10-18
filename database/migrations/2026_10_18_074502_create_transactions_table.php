<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code', 50)->unique();
            $table->foreignId('saver_id')->constrained('savers')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->enum('transaction_type', ['deposit', 'withdrawal']);
            $table->bigInteger('amount');
            $table->bigInteger('balance_before');
            $table->bigInteger('balance_after');
            $table->string('description', 255)->nullable();
            $table->dateTime('transaction_date');
            $table->timestamps();
        });

        // Add check constraint directly on MySQL table (amount > 0) to comply with UKK Rubric 1.3
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE transactions ADD CONSTRAINT chk_transaction_amount_positive CHECK (amount > 0)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
