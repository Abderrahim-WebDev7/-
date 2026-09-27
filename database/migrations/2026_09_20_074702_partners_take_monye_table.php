<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_payments', function (Blueprint $table) {
            $table->id();

            // ✅ ربط بجدول capitals وليس partners
            $table->foreignId('capital_id')
                ->constrained('capitals')
                ->cascadeOnDelete();

            // مبلغ السحب
            $table->decimal('amount', 15, 2);

            // تاريخ السحب
            $table->dateTime('payment_date')->useCurrent();

            // ملاحظة اختيارية
            $table->text('notes')->nullable();

            $table->timestamps();

            // فهارس
            $table->index('payment_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_payments');
    }
};