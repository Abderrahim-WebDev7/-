<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            
            // التاريخ
            $table->date('date');
            
            // المورد (البائع)
            $table->string('supplier_name');
            
            // نوع البيض
            $table->string('egg_type')->nullable();
            
            // الكمية
            $table->string('qty_type')->default('plate');
            $table->integer('qty_count')->default(0);
            
            // سعر الشراء
            $table->decimal('purchase_price', 10, 2);
            
            // المشتري (الزبون)
            $table->string('buyer_name');
            
            // سعر البيع
            $table->decimal('sale_price', 10, 2);
            
            // الإجماليات
            $table->decimal('total_purchase', 10, 2);
            $table->decimal('total_sale', 10, 2);
            $table->decimal('profit', 10, 2);
            
            // تتبع الكمية
            $table->integer('total_qty')->default(0);
            $table->integer('taken_qty')->default(0);
            $table->integer('remaining_qty')->default(0);
            $table->string('quantity_status')->default('full');
            
            // حالة الخروج
            $table->boolean('is_exited')->default(false);
            
            // حالة الدفع
            //$table->boolean('supplier_paid')->default(false);
            //$table->boolean('buyer_paid')->default(false);
            //$table->date('supplier_paid_date')->nullable();
            //$table->date('buyer_paid_date')->nullable();
            
            // ملاحظات
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};