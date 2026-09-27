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
        Schema::create('capitals', function (Blueprint $table) {
            $table->id();
            
            // اسم الشريك
            $table->string('partner_name', 255)
                  ->comment('اسم الشريك صاحب رأس المال');
            
            // المبلغ - نوع NUMERIC ليتوافق مع type="number"
            $table->decimal('amount', 15, 2)
                  ->default(0)
                  ->comment('مبلغ رأس المال بالدينار الجزائري');
            
            // تاريخ الإدخال
            $table->date('entry_date')
                  ->comment('تاريخ إدخال رأس المال');
            
            // ملاحظات
            $table->text('notes')
                  ->nullable()
                  ->comment('ملاحظات إضافية');
            
            // الطوابع الزمنية
            $table->timestamps();
            
            // الفهارس لتسريع البحث
            $table->index('entry_date');
            $table->index('partner_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capitals');
    }
};