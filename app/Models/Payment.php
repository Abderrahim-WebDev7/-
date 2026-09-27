<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    
    protected $fillable = [
        'worker_id',
        'date',
        'amount',
        'type',
        'remaining_after',
        'notes'
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'remaining_after' => 'decimal:2',
        'date' => 'date:Y-m-d',
    ];
    
    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}