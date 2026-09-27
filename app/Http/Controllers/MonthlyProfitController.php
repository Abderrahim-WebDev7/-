<?php

namespace App\Http\Controllers;

use App\Models\MonthlyProfit;
use App\Models\PartnerPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MonthlyProfitController extends Controller
{
    /**
     * ✅ جلب جميع الأرباح الشهرية
     */
    public function index()
{
    try {
        $payments = PartnerPayment::with('capital')
            ->orderBy('payment_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        return response()->json($payments);
    } catch (\Exception $e) {
        Log::error('Error fetching partner payments: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    /**
     * ✅ جلب أرباح فترة محددة
     */
    public function show($year, $month)
    {
        try {
            $profit = MonthlyProfit::where('year', $year)
                ->where('month', $month)
                ->first();

            if (!$profit) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا توجد بيانات لهذه الفترة'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'profit' => $profit
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching monthly profit: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * ✅ حفظ أو تحديث أرباح شهر
     */
    public function store(Request $request)
{
    try {
        Log::info('Monthly profit data received:', $request->all());

        $validated = $request->validate([
            'year' => 'required|string|max:4',
            'month' => 'required|string|max:2',
            'total_purchases' => 'nullable|numeric',
            'total_sales' => 'nullable|numeric',
            'net_profit' => 'nullable|numeric',
            'transactions_count' => 'nullable|integer',
            'buyers_unpaid' => 'nullable|numeric',
            'suppliers_unpaid' => 'nullable|numeric',
            'wages_paid' => 'nullable|numeric',
            'other_costs' => 'nullable|numeric',
            'total_profit_with_loss' => 'nullable|numeric',
            'notes' => 'nullable|string'
        ]);

        // ✅ البحث عن سجل موجود أو إنشاء جديد
        $profit = MonthlyProfit::updateOrCreate(
            [
                'year' => $validated['year'],
                'month' => $validated['month']
            ],
            [
                'total_purchases' => $validated['total_purchases'] ?? 0,
                'total_sales' => $validated['total_sales'] ?? 0,
                'net_profit' => $validated['net_profit'] ?? 0,
                'transactions_count' => $validated['transactions_count'] ?? 0,
                'buyers_unpaid' => $validated['buyers_unpaid'] ?? 0,
                'suppliers_unpaid' => $validated['suppliers_unpaid'] ?? 0,
                'wages_paid' => $validated['wages_paid'] ?? 0,
                'other_costs' => $validated['other_costs'] ?? 0,
                'total_profit_with_loss' => $validated['total_profit_with_loss'] ?? 0,
                'notes' => $validated['notes'] ?? null
            ]
        );

        Log::info('Monthly profit saved/updated:', $profit->toArray());

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ/تحديث الأرباح الشهرية بنجاح',
            'profit' => $profit
        ], 201);

    } catch (\Exception $e) {
        Log::error('Error saving monthly profit: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * ✅ حذف أرباح فترة
     */
    public function destroy($id)
    {
        try {
            $profit = MonthlyProfit::find($id);
            if (!$profit) {
                return response()->json(['error' => 'السجل غير موجود'], 404);
            }

            $profit->delete();
            return response()->json([
                'success' => true,
                'message' => 'تم حذف السجل بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting monthly profit: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}