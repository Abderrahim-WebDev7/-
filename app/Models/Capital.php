<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Capital extends Model
{
    protected $fillable = [
        'partner_name',
        'amount',
        'entry_date',
        'notes'
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'entry_date' => 'date'
    ];
}