<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * هجرة جدول الحضور (attendance)
 * --------------------------------
 * يسجّل حالة كل عامل لكل يوم (حاضر / غائب / راحة مدفوعة).
 * مرتبط بجدول العمّال (workers) عبر worker_id.
 * القيد الفريد (worker_id + date) يمنع تكرار سجل لنفس العامل في نفس اليوم.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance', function (Blueprint $table) {

            // ---- المفتاح الأساسي ----
            $table->id();

            // ---- المفتاح الأجنبي ----
            $table->foreignId('worker_id')
                  ->constrained('workers')
                  ->cascadeOnDelete();
            // يربط السجل بعامل محدد
            // cascadeOnDelete: عند حذف العامل تُحذف كل سجلات حضوره تلقائيًا

            // ---- بيانات الحضور ----
            $table->date('date');
            // تاريخ اليوم — مثال: 2026-07-24

            $table->enum('status', ['present', 'absent', 'rest']);
            // حالة العامل في هذا اليوم:
            //   present → حاضر
            //   absent  → غائب (لا يُحتسب في الأجرة)
            //   rest    → راحة مدفوعة (يُحتسب في الأجرة مثل الحضور)

            $table->boolean('paid')->default(false);
            // هل تم احتساب هذا اليوم ضمن دفعة أجرة مؤكّدة؟
            // false = بانتظار الدفع
            // true  = تم الدفع عبر جدول payments

            // ---- الطوابع الزمنية ----
            $table->timestamps();

            // ---- القيود ----
            $table->unique(['worker_id', 'date']);
            // يضمن سجلًا واحدًا فقط لكل عامل في كل يوم

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};