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
        Schema::table('daily_closings', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('notes');
        });

        if (\Illuminate\Support\Facades\DB::getDriverName() === 'mysql') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE daily_closings MODIFY COLUMN status ENUM('draft', 'submitted', 'approved', 'discrepancy', 'rejected') DEFAULT 'draft'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (\Illuminate\Support\Facades\DB::getDriverName() === 'mysql') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE daily_closings MODIFY COLUMN status ENUM('draft', 'submitted', 'approved', 'discrepancy') DEFAULT 'draft'");
        }

        Schema::table('daily_closings', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });
    }
};
