<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_profits', function (Blueprint $table) {
            $table->id();

            // ✅ الفترة
            $table->string('year', 4)->comment('السنة (مثال: 2026)');
            $table->string('month', 2)->comment('الشهر (مثال: 09)');

            // ✅ الإحصائيات
            $table->decimal('total_purchases', 15, 2)->default(0)->comment('إجمالي المشتريات');
            $table->decimal('total_sales', 15, 2)->default(0)->comment('إجمالي المبيعات');
            $table->decimal('net_profit', 15, 2)->default(0)->comment('الربح الصافي');
            $table->integer('transactions_count')->default(0)->comment('عدد المعاملات');
            $table->decimal('buyers_unpaid', 15, 2)->default(0)->comment('المشترين غير المدفوعين');
            $table->decimal('suppliers_unpaid', 15, 2)->default(0)->comment('الموردين غير المدفوعين');
            $table->decimal('wages_paid', 15, 2)->default(0)->comment('أجور العمال المدفوعة');
            $table->decimal('other_costs', 15, 2)->default(0)->comment('التكاليف الأخرى');
            $table->decimal('total_profit_with_loss', 15, 2)->default(0)->comment('إجمالي الأرباح');

            // ✅ ملاحظات
            $table->text('notes')->nullable()->comment('ملاحظات');

            $table->timestamps();

            // ✅ فهرس مركب لمنع التكرار
            $table->unique(['year', 'month'], 'unique_year_month');

            // ✅ فهارس للبحث
            $table->index('year');
            $table->index('month');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_profits');
    }
};