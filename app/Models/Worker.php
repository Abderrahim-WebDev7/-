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

    protected $appends = [
        'paid_amount',
    ];

    protected $casts = [
        'wage' => 'decimal:2',
        'total_salary' => 'decimal:2',
        'remaining_salary' => 'decimal:2',
        'present_days' => 'integer',
        'absent_days' => 'integer',
        'rest_days' => 'integer',
        'last_attendance_date' => 'date:Y-m-d',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getPaidAmountAttribute()
    {
        if ($this->relationLoaded('payments')) {
            return (float) $this->payments->sum('amount');
        }

        return (float) ($this->payments()->sum('amount') ?? 0);
    }

    public function markAttendance($status)
    {
        $today = now()->toDateString();
        $lastDate = $this->last_attendance_date
            ? $this->last_attendance_date->toDateString()
            : null;

        Attendance::updateOrCreate(
            [
                'worker_id' => $this->id,
                'date' => $today,
            ],
            [
                'status' => $status,
                'paid' => false,
            ]
        );

        if ($lastDate === $today && $this->current_status === $status) {
            $this->recalculateSalary();
            $this->save();
            return false;
        }

        if ($lastDate === $today) {
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
        $this->recalculateSalary();
        $this->save();

        return true;
    }

    public function recalculateSalary(): void
    {
        $total = ($this->present_days + $this->rest_days) * (float) $this->wage;
        $this->total_salary = $total;
        $this->remaining_salary = $total - $this->paid_amount;
    }

    private function revertPreviousStatus()
    {
        switch ($this->current_status) {
            case 'present':
                $this->present_days = max(0, $this->present_days - 1);
                break;
            case 'absent':
                $this->absent_days = max(0, $this->absent_days - 1);
                break;
            case 'rest':
                $this->rest_days = max(0, $this->rest_days - 1);
                break;
        }
    }
}
