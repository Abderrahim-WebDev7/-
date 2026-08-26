<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = [
        'full_name',
        'phone',
        'address',
        'type',
        'notes'
    ];
    
    protected $casts = [
        'type' => 'string'
    ];
    
    // Scope للتصفية
    public function scopeSuppliers($query)
    {
        return $query->where('type', 'supplier')->orWhere('type', 'both');
    }
    
    public function scopeBuyers($query)
    {
        return $query->where('type', 'buyer')->orWhere('type', 'both');
    }
}