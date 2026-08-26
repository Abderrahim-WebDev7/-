<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * إضافة أعمدة حالة الدفع لجدول المعاملات
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // التحقق من وجود الأعمدة قبل إضافتها لتجنب الأخطاء
            if (!Schema::hasColumn('transactions', 'supplier_paid')) {
                $table->boolean('supplier_paid')->default(false)->after('is_exited')
                      ->comment('هل تم دفع البائع؟');
            }
            
            if (!Schema::hasColumn('transactions', 'buyer_paid')) {
                $table->boolean('buyer_paid')->default(false)->after('supplier_paid')
                      ->comment('هل تم دفع المشتري؟');
            }
            
            if (!Schema::hasColumn('transactions', 'supplier_paid_date')) {
                $table->date('supplier_paid_date')->nullable()->after('buyer_paid')
                      ->comment('تاريخ دفع البائع');
            }
            
            if (!Schema::hasColumn('transactions', 'buyer_paid_date')) {
                $table->date('buyer_paid_date')->nullable()->after('supplier_paid_date')
                      ->comment('تاريخ دفع المشتري');
            }
        });
    }

    /**
     * Reverse the migrations.
     * حذف الأعمدة في حالة التراجع
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $columns = ['supplier_paid', 'buyer_paid', 'supplier_paid_date', 'buyer_paid_date'];
            
            // التحقق من وجود الأعمدة قبل حذفها
            foreach ($columns as $column) {
                if (Schema::hasColumn('transactions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};