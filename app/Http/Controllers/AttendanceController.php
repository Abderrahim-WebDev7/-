<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\Attendance;
//use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class AttendanceController extends Controller
{
    /**
     * تسجيل حالة حضور عامل لليوم (حاضر / غائب / راحة)
     * يُستدعى عند الضغط على أزرار "حاضر اليوم" / "غائب اليوم" / "راحة"
     */
    public function markToday(Request $request, Worker $worker): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:present,absent,rest',
        ]);

        $today = now()->toDateString();

        // تحديث السجل إن وُجد، أو إنشاؤه
        Attendance::updateOrCreate(
            [
                'worker_id' => $worker->id,
                'date'      => $today,
            ],
            [
                'status' => $validated['status'],
                'paid'   => false,
            ]
        );

        $labels = [
            'present' => 'تم تسجيل الحضور لليوم',
            'absent'  => 'تم تسجيل الغياب لليوم',
            'rest'    => 'تم تسجيل يوم راحة مدفوع',
        ];

        return redirect()->route('workers.index')
                         ->with('success', $labels[$validated['status']]);
    }
}