<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // إضافة عمودين منفصلين للمبالغ المدفوعة
            if (!Schema::hasColumn('transactions', 'buyer_paid_amount')) {
                $table->decimal('buyer_paid_amount', 10, 2)->default(0)->after('buyer_paid_date')
                      ->comment('المبلغ المدفوع من المشتري');
            }
            if (!Schema::hasColumn('transactions', 'supplier_paid_amount')) {
                $table->decimal('supplier_paid_amount', 10, 2)->default(0)->after('buyer_paid_amount')
                      ->comment('المبلغ المدفوع للبائع');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['buyer_paid_amount', 'supplier_paid_amount']);
        });
    }
};