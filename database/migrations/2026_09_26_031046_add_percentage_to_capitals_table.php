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
        Schema::table('capitals', function (Blueprint $table) {
            // ✅ عمود النسبة المئوية للشريك
            if (!Schema::hasColumn('capitals', 'percentage')) {
                $table->decimal('percentage', 8, 2)
                      ->default(0)
                      ->after('amount')
                      ->comment('النسبة المئوية للشريك من إجمالي رأس المال');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('capitals', function (Blueprint $table) {
            if (Schema::hasColumn('capitals', 'percentage')) {
                $table->dropColumn('percentage');
            }
        });
    }
};