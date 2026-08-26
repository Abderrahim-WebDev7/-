<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    protected $table = 'workers';
    
    protected $fillable = [
        'name',
        'phone',
        'wage',
        'present_days',
        'absent_days',
        'rest_days',
        'total_salary',
        'remaining_salary',
        'last_attendance_date',
        'current_status'
    ];
    
    protected $casts = [
        'wage' => 'decimal:2',
        'total_salary' => 'decimal:2',
        'remaining_salary' => 'decimal:2',
        'present_days' => 'integer',
        'absent_days' => 'integer',
        'rest_days' => 'integer',
        'last_attendance_date' => 'date'
    ];
    
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    public function getPaidAmountAttribute()
    {
        return $this->payments()->sum('amount') ?? 0;
    }
    
    public function getTotalSalaryAttribute()
    {
        $days = $this->present_days + $this->rest_days;
        return $days * $this->wage;
    }
    
    public function getRemainingSalaryAttribute()
    {
        return $this->total_salary - $this->paid_amount;
    }
    
    public function markAttendance($status)
    {
        $today = now()->toDateString();
        
        if ($this->last_attendance_date === $today && $this->current_status === $status) {
            return false;
        }
        
        if ($this->last_attendance_date === $today) {
            $this->revertPreviousStatus();
        }
        
        switch ($status) {
            case 'present':
                $this->present_days += 1;
                break;
            case 'absent':
                $this->absent_days += 1;
                break;
            case 'rest':
                $this->rest_days += 1;
                break;
        }
        
        $this->current_status = $status;
        $this->last_attendance_date = $today;
        $this->save();
        
        return true;
    }
    
    private function revertPreviousStatus()
    {
        switch ($this->current_status) {
            case 'present':
                $this->present_days -= 1;
                break;
            case 'absent':
                $this->absent_days -= 1;
                break;
            case 'rest':
                $this->rest_days -= 1;
                break;
        }
    }
}