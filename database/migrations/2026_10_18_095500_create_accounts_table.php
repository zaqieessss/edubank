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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saver_id')->unique()->constrained('savers')->onDelete('restrict');
            $table->string('account_number', 30)->unique(); // e.g. RPL-2026-0001
            $table->string('account_type', 30)->default('student_savings'); // regular, student_savings, syariah
            $table->bigInteger('balance')->default(10000); // Saldo berjalan
            $table->bigInteger('minimum_balance')->default(10000); // Batas saldo mengendap
            $table->enum('status', ['active', 'dormant', 'frozen', 'closed'])->default('active');
            $table->date('opened_date');
            $table->date('closed_date')->nullable();
            $table->string('qr_code_path', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Add foreign key account_id to transactions table if column doesn't exist
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('account_id')->nullable()->after('saver_id')->constrained('accounts')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropColumn('account_id');
        });

        Schema::dropIfExists('accounts');
    }
};
