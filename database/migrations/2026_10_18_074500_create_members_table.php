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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('student_id_number', 30)->unique(); // NIS
            $table->string('full_name', 100);
            $table->enum('grade_level', ['10', '11', '12', 'alumni'])->default('10');
            $table->string('class_name', 30); // e.g. "X RPL 1"
            $table->string('major', 20); // RPL, TKJ, AKL, MPLB, DKV, BD
            $table->string('cohort', 4)->default('2026');
            $table->enum('gender', ['male', 'female']);
            $table->string('email', 100)->unique();
            $table->string('phone_number', 20)->nullable();
            $table->text('address')->nullable();
            $table->enum('registration_status', ['unregistered', 'registered'])->default('unregistered');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
