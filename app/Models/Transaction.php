<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'date',
        'supplier_name',
        'egg_type',
        'qty_type',
        'qty_count',
        'purchase_price',
        'buyer_name',
        'sale_price',
        'total_purchase',
        'total_sale',
        'profit',
        'is_exited',
        'notes',
        'total_qty',
        'taken_qty',
        'remaining_qty',
        'quantity_status',
        // أعمدة الدفع الجديدة
        'supplier_paid',
        'buyer_paid',
        'supplier_paid_date',
        'buyer_paid_date',
        'paid_amount',
        'buyer_paid_amount',    // أضف هذا
        'supplier_paid_amount'  // أضف هذا
    ];
    
    protected $casts = [
        'is_exited' => 'boolean',
        'supplier_paid' => 'boolean',
        'buyer_paid' => 'boolean',
        'date' => 'date',
        'supplier_paid_date' => 'date',
        'buyer_paid_date' => 'date',
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'total_purchase' => 'decimal:2',
        'total_sale' => 'decimal:2',
        'profit' => 'decimal:2',
        'total_qty' => 'integer',
        'taken_qty' => 'integer',
        'remaining_qty' => 'integer',
        'paid_amount' => 'decimal:2',
        'buyer_paid_amount' => 'decimal:2',    // أضف هذا
        'supplier_paid_amount' => 'decimal:2'  // أضف هذا
    ];
    
    // تحديث حالة الكمية
    public function updateQuantityStatus()
    {
        if ($this->remaining_qty <= 0) {
            $this->quantity_status = 'empty';
        } elseif ($this->taken_qty > 0 && $this->remaining_qty > 0) {
            $this->quantity_status = 'partial';
        } else {
            $this->quantity_status = 'full';
        }
        $this->save();
    }
    
    // تأكيد دفع البائع
    public function markSupplierPaid()
    {
        $this->supplier_paid = true;
        $this->supplier_paid_date = now()->toDateString();
        $this->save();
    }
    
    // تأكيد دفع المشتري
    public function markBuyerPaid()
    {
        $this->buyer_paid = true;
        $this->buyer_paid_date = now()->toDateString();
        $this->save();
    }
}