<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Capital extends Model
{
    protected $table = 'capitals';

    protected $fillable = [
        'partner_name',
        'amount',
        'percentage',
        'entry_date',
        'notes'
    ];

    protected $casts = [
        'amount'     => 'float',
        'percentage' => 'float',
        'entry_date' => 'date'
    ];
}