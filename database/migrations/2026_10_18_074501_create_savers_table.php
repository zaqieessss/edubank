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
        Schema::create('savers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->unique()->constrained('members')->onDelete('restrict');
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('restrict');
            $table->string('account_number', 30)->unique(); // Format: [MAJOR]-[COHORT]-[SEQ] (e.g. RPL-2026-0001)
            $table->string('student_id_number', 30);
            $table->string('full_name', 100);
            $table->enum('grade_level', ['10', '11', '12', 'alumni'])->default('10');
            $table->string('class_name', 30);
            $table->string('major', 20);
            $table->string('cohort', 4);
            $table->string('security_pin'); // Hashed 6-digit PIN
            $table->string('qr_code_path')->nullable();
            $table->date('joined_date');
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savers');
    }
};
