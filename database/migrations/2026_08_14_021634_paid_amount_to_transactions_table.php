<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * إضافة عمود paid_amount إلى جدول transactions لتتبع المبلغ المدفوع
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // التحقق من وجود العمود قبل إضافته
            if (!Schema::hasColumn('transactions', 'paid_amount')) {
                $table->decimal('paid_amount', 10, 2)->default(0)->after('notes')
                      ->comment('المبلغ المدفوع من هذه المعاملة');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'paid_amount')) {
                $table->dropColumn('paid_amount');
            }
        });
    }
};