<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerPayment extends Model
{
    protected $table = 'partner_payments';

    protected $fillable = [
        'capital_id',      // ✅ تغيير من partner_id
        'amount',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    /**
     * ✅ العلاقة مع جدول capitals
     */
    public function capital(): BelongsTo
    {
        return $this->belongsTo(Capital::class, 'capital_id');
    }
}