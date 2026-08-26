<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workers', function (Blueprint $table) {
            if (!Schema::hasColumn('workers', 'present_days')) {
                $table->integer('present_days')->default(0)->after('wage');
            }
            if (!Schema::hasColumn('workers', 'absent_days')) {
                $table->integer('absent_days')->default(0)->after('present_days');
            }
            if (!Schema::hasColumn('workers', 'rest_days')) {
                $table->integer('rest_days')->default(0)->after('absent_days');
            }
            if (!Schema::hasColumn('workers', 'total_salary')) {
                $table->decimal('total_salary', 10, 2)->default(0)->after('rest_days');
            }
            if (!Schema::hasColumn('workers', 'remaining_salary')) {
                $table->decimal('remaining_salary', 10, 2)->default(0)->after('total_salary');
            }
            if (!Schema::hasColumn('workers', 'last_attendance_date')) {
                $table->date('last_attendance_date')->nullable()->after('remaining_salary');
            }
            if (!Schema::hasColumn('workers', 'current_status')) {
                $table->string('current_status')->default('pending')->after('last_attendance_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('workers', function (Blueprint $table) {
            $table->dropColumn([
                'present_days', 'absent_days', 'rest_days',
                'total_salary', 'remaining_salary',
                'last_attendance_date', 'current_status'
            ]);
        });
    }
};