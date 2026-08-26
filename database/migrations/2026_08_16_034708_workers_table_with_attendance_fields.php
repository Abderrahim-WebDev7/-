<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workers', function (Blueprint $table) {
            // إضافة أعمدة لحضور العامل
            $table->integer('present_days')->default(0)->after('wage');
            $table->integer('absent_days')->default(0)->after('present_days');
            $table->integer('rest_days')->default(0)->after('absent_days');
            $table->decimal('total_salary', 10, 2)->default(0)->after('rest_days');
            $table->decimal('remaining_salary', 10, 2)->default(0)->after('total_salary');
            $table->date('last_attendance_date')->nullable()->after('remaining_salary');
            $table->string('current_status')->default('pending')->after('last_attendance_date');
        });
    }

    public function down(): void
    {
        Schema::table('workers', function (Blueprint $table) {
            $table->dropColumn([
                'present_days',
                'absent_days',
                'rest_days',
                'total_salary',
                'remaining_salary',
                'last_attendance_date',
                'current_status'
            ]);
        });
    }
};