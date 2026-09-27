<?php

namespace App\Http\Controllers;

use App\Models\Capital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CapitalController extends Controller
{
    // جلب جميع رؤوس الأموال
    public function index()
    {
        try {
            $capitals = Capital::orderBy('entry_date', 'desc')->get();
            return response()->json($capitals);
        } catch (\Exception $e) {
            Log::error('Error fetching capitals: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // إضافة رأس مال جديد
    public function store(Request $request)
{
    try {
        Log::info('Capital data received:', $request->all());

        $validated = $request->validate([
            'partner_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'entry_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $amount = floatval($validated['amount']);

        if ($amount <= 0) {
            return response()->json([
                'success' => false,
                'error' => 'المبلغ يجب أن يكون أكبر من صفر'
            ], 422);
        }

        // ✅ حساب النسبة المئوية
        // إجمالي رأس المال الحالي + المبلغ الجديد
        $currentTotal = Capital::sum('amount');
        $newTotal = $currentTotal + $amount;
        $percentage = $newTotal > 0 ? ($amount / $newTotal) * 100 : 0;

        $capital = Capital::create([
            'partner_name' => $validated['partner_name'],
            'amount' => $amount,
            'percentage' => round($percentage, 2),
            'entry_date' => $validated['entry_date'],
            'notes' => $validated['notes'] ?? null
        ]);

        // ✅ إعادة حساب النسب لجميع الشركاء بعد الإضافة
        $this->recalculateAllPercentages();

        Log::info('Capital created:', $capital->toArray());

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة رأس المال بنجاح',
            'capital' => $capital->fresh()
        ], 201);

    } catch (\Exception $e) {
        Log::error('Error creating capital: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

    // تحديث رأس مال
    public function update(Request $request, $id)
{
    try {
        $capital = Capital::find($id);
        if (!$capital) {
            return response()->json(['error' => 'رأس المال غير موجود'], 404);
        }

        $validated = $request->validate([
            'partner_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'entry_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $amount = floatval($validated['amount']);

        if ($amount <= 0) {
            return response()->json([
                'success' => false,
                'error' => 'المبلغ يجب أن يكون أكبر من صفر'
            ], 422);
        }

        $capital->update([
            'partner_name' => $validated['partner_name'],
            'amount' => $amount,
            'entry_date' => $validated['entry_date'],
            'notes' => $validated['notes'] ?? null
        ]);

        // ✅ إعادة حساب النسب لجميع الشركاء
        $this->recalculateAllPercentages();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث رأس المال بنجاح',
            'capital' => $capital->fresh()
        ]);

    } catch (\Exception $e) {
        Log::error('Error updating capital: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    // حذف رأس مال
    public function destroy($id)
{
    try {
        $capital = Capital::find($id);
        if (!$capital) {
            return response()->json(['error' => 'رأس المال غير موجود'], 404);
        }

        $capital->delete();

        // ✅ إعادة حساب النسب لجميع الشركاء بعد الحذف
        $this->recalculateAllPercentages();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف رأس المال بنجاح'
        ]);

    } catch (\Exception $e) {
        Log::error('Error deleting capital: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    // جلب إجمالي رأس المال
    public function total()
    {
        try {
            $total = Capital::sum('amount');
            return response()->json([
                'total' => $total,
                'count' => Capital::count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching total capital: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
 * ✅ إعادة حساب النسب المئوية لجميع الشركاء
 */
private function recalculateAllPercentages()
{
    try {
        $totalCapital = Capital::sum('amount');

        if ($totalCapital <= 0) {
            return;
        }

        $capitals = Capital::all();

        foreach ($capitals as $capital) {
            $percentage = ($capital->amount / $totalCapital) * 100;
            $capital->percentage = round($percentage, 2);
            $capital->save();
        }

        Log::info('✅ تم إعادة حساب النسب المئوية لجميع الشركاء');
    } catch (\Exception $e) {
        Log::error('❌ خطأ في إعادة حساب النسب: ' . $e->getMessage());
    }
}
}