<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyProfit extends Model
{
    protected $table = 'monthly_profits';

    protected $fillable = [
        'year',
        'month',
        'total_purchases',
        'total_sales',
        'net_profit',
        'transactions_count',
        'buyers_unpaid',
        'suppliers_unpaid',
        'wages_paid',
        'other_costs',
        'total_profit_with_loss',
        'notes'
    ];

    protected $casts = [
        'year' => 'string',
        'month' => 'string',
        'total_purchases' => 'float',
        'total_sales' => 'float',
        'net_profit' => 'float',
        'transactions_count' => 'integer',
        'buyers_unpaid' => 'float',
        'suppliers_unpaid' => 'float',
        'wages_paid' => 'float',
        'other_costs' => 'float',
        'total_profit_with_loss' => 'float'
    ];

    /**
     * ✅ جلب اسم الشهر بالعربية
     */
    public function getMonthNameAttribute()
    {
        $months = [
            '01' => 'جانفي',
            '02' => 'فيفري',
            '03' => 'مارس',
            '04' => 'أفريل',
            '05' => 'ماي',
            '06' => 'جوان',
            '07' => 'جويلية',
            '08' => 'أوت',
            '09' => 'سبتمبر',
            '10' => 'أكتوبر',
            '11' => 'نوفمبر',
            '12' => 'ديسمبر'
        ];

        return $months[$this->month] ?? $this->month;
    }

    /**
     * ✅ جلب الفترة كاملة
     */
    public function getPeriodAttribute()
    {
        return $this->year . '-' . $this->month;
    }
}