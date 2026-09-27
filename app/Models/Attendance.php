<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';
    
    protected $fillable = ['worker_id', 'date', 'status', 'paid'];
    
    protected $casts = [
        'paid' => 'boolean',
        'date' => 'date:Y-m-d',
    ];
}