<?php

namespace App\Http\Controllers;

use App\Models\PartnerPayment;
use App\Models\Capital;
use App\Models\Transaction;
use App\Models\Cost;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PartnerPaymentController extends Controller
{
    // جلب جميع السحوبات
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
     * ✅ حساب صافي الأرباح (لجميع الفترات)
     */
    private function calculateTotalProfit($year = null, $month = null)
    {
        try {
            $query = Transaction::query();
            $costQuery = Cost::query();
            $paymentQuery = Payment::query();

            if ($year && $month) {
                $query->whereYear('date', $year)->whereMonth('date', $month);
                $costQuery->whereYear('date', $year)->whereMonth('date', $month);
                $paymentQuery->whereYear('date', $year)->whereMonth('date', $month);

                Log::info('📅 تصفية الأرباح للفترة:', [
                    'year' => $year,
                    'month' => $month
                ]);
            } else {
                Log::info('📅 حساب الأرباح لجميع الفترات');
            }

            $transactions = $query->get();
            $costs = $costQuery->get();
            $payments = $paymentQuery->get();

            $totalPurchases = 0;
            foreach ($transactions as $t) {
                if ($t->type === 'purchase' || (!$t->type && $t->purchase_price > 0)) {
                    $qtyCount = (int) $t->qty_count;
                    $qtyType = $t->qty_type ?: 'plate';
                    $plates = ($qtyType === 'carton12') ? $qtyCount * 12 : $qtyCount;
                    $totalPurchases += $plates * floatval($t->purchase_price);
                }
            }

            $totalSales = 0;
            foreach ($transactions as $t) {
                if ($t->type === 'sale' || (!$t->type && $t->sale_price > 0)) {
                    $qtyCount = (int) $t->qty_count;
                    $qtyType = $t->qty_type ?: 'plate';
                    $plates = ($qtyType === 'carton12') ? $qtyCount * 12 : $qtyCount;
                    $totalSales += $plates * floatval($t->sale_price);
                }
            }

            $netProfit = $totalSales - $totalPurchases;
            $totalOtherCosts = $costs->sum('amount');
            $totalWagesPaid = $payments->where('amount', '>', 0)->sum('amount');
            $totalProfitWithLoss = $netProfit - $totalOtherCosts - $totalWagesPaid;

            Log::info('💰 حساب صافي الأرباح:', [
                'total_purchases' => $totalPurchases,
                'total_sales' => $totalSales,
                'net_profit' => $netProfit,
                'total_costs' => $totalOtherCosts,
                'total_wages' => $totalWagesPaid,
                'total_profit_with_loss' => $totalProfitWithLoss
            ]);

            return $totalProfitWithLoss;

        } catch (\Exception $e) {
            Log::error('❌ Error calculating total profit: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * ✅ إضافة سحب جديد
     */
    public function store(Request $request)
{
    try {
        Log::info('Partner payment data received:', $request->all());

        $validated = $request->validate([
            'capital_id' => 'required|exists:capitals,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'total_profit' => 'nullable|numeric'  // ✅ حقل جديد
        ]);

        $amount = floatval($validated['amount']);

        if ($amount <= 0) {
            return response()->json([
                'success' => false,
                'error' => 'المبلغ يجب أن يكون أكبر من صفر'
            ], 422);
        }

        $capital = Capital::find($validated['capital_id']);
        if (!$capital) {
            return response()->json([
                'success' => false,
                'error' => 'الشريك غير موجود'
            ], 404);
        }

        // ============================================
        //  ✅ الأرباح: من الفرونت إند أو من الباك إند
        // ============================================
        $totalProfitWithLoss = 0;
        
        if ($request->has('total_profit') && $request->input('total_profit') !== null) {
            // ✅ استخدم القيمة المرسلة من الفرونت إند
            $totalProfitWithLoss = floatval($request->input('total_profit'));
            Log::info('📊 استخدام الأرباح من الفرونت إند:', ['value' => $totalProfitWithLoss]);
        } else {
            // احتياطي: احسبها في الباك إند
            $totalProfitWithLoss = $this->calculateTotalProfit();
            Log::info('📊 استخدام الأرباح المحسوبة في الباك إند:', ['value' => $totalProfitWithLoss]);
        }

        // ============================================
        //  ✅ حساب المبلغ المستحق بنفس طريقة الفرونت إند
        //  المبلغ المستحق = رأس المال + (الأرباح × النسبة / 100)
        // ============================================
        $totalCapital = Capital::sum('amount');
        $capitalAmount = floatval($capital->amount);
        
        // ✅ استخدم النسبة المحفوظة في قاعدة البيانات (نفس الفرونت)
        if ($capital->percentage !== null) {
            $percentage = floatval($capital->percentage);
        } else {
            // احتياطي: احسبها
            $percentage = $totalCapital > 0 ? ($capitalAmount / $totalCapital) * 100 : 0;
        }

        $profitShare = ($totalProfitWithLoss * $percentage) / 100;
        $totalEntitlement = $capitalAmount + $profitShare;

        // ✅ إجمالي المسحوب
        $totalWithdrawn = PartnerPayment::where('capital_id', $capital->id)
            ->sum('amount');

        $remaining = $totalEntitlement - floatval($totalWithdrawn);

        Log::info('💰 حساب المبلغ المستحق:', [
            'capital_amount' => $capitalAmount,
            'percentage' => $percentage,
            'total_profit' => $totalProfitWithLoss,
            'profit_share' => $profitShare,
            'total_entitlement' => $totalEntitlement,
            'total_withdrawn' => $totalWithdrawn,
            'remaining' => $remaining
        ]);

        // ✅ التحقق
        if ($amount > $remaining) {
            return response()->json([
                'success' => false,
                'error' => "المبلغ ({$amount} دج) يتجاوز المبلغ المستحق المتبقي ({$remaining} دج)"
            ], 422);
        }

        // ✅ الحفظ
        $payment = PartnerPayment::create([
            'capital_id' => $validated['capital_id'],
            'amount' => $amount,
            'payment_date' => $validated['payment_date'] ?? now(),
            'notes' => $validated['notes'] ?? null
        ]);

        $payment->load('capital');

        Log::info('Partner payment created:', $payment->toArray());

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل السحب بنجاح',
            'payment' => $payment
        ], 201);

    } catch (\Exception $e) {
        Log::error('Error creating partner payment: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * ✅ تحديث سحب شريك
     */
    public function update(Request $request, $id)
{
    try {
        Log::info('Partner payment update data received:', $request->all());

        $payment = PartnerPayment::find($id);
        if (!$payment) {
            return response()->json([
                'success' => false,
                'error' => 'السحب غير موجود'
            ], 404);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'total_profit' => 'nullable|numeric'  // ✅ حقل جديد
        ]);

        $amount = floatval($validated['amount']);

        if ($amount <= 0) {
            return response()->json([
                'success' => false,
                'error' => 'المبلغ يجب أن يكون أكبر من صفر'
            ], 422);
        }

        $capital = Capital::find($payment->capital_id);
        if (!$capital) {
            return response()->json([
                'success' => false,
                'error' => 'الشريك غير موجود'
            ], 404);
        }

        // ✅ الأرباح من الفرونت أو الباك
        $totalProfitWithLoss = 0;
        if ($request->has('total_profit') && $request->input('total_profit') !== null) {
            $totalProfitWithLoss = floatval($request->input('total_profit'));
        } else {
            $totalProfitWithLoss = $this->calculateTotalProfit();
        }

        // ✅ نفس طريقة الفرونت
        $totalCapital = Capital::sum('amount');
        $capitalAmount = floatval($capital->amount);
        
        $percentage = $capital->percentage !== null
            ? floatval($capital->percentage)
            : ($totalCapital > 0 ? ($capitalAmount / $totalCapital) * 100 : 0);

        $profitShare = ($totalProfitWithLoss * $percentage) / 100;
        $totalEntitlement = $capitalAmount + $profitShare;

        // ✅ إجمالي المسحوب (باستثناء هذا السحب)
        $totalWithdrawnExceptThis = PartnerPayment::where('capital_id', $capital->id)
            ->where('id', '!=', $id)
            ->sum('amount');

        $maxAllowed = $totalEntitlement - floatval($totalWithdrawnExceptThis);

        Log::info('💰 حساب المبلغ المسموح (تحديث):', [
            'capital_amount' => $capitalAmount,
            'percentage' => $percentage,
            'total_profit' => $totalProfitWithLoss,
            'profit_share' => $profitShare,
            'total_entitlement' => $totalEntitlement,
            'total_withdrawn_except_this' => $totalWithdrawnExceptThis,
            'max_allowed' => $maxAllowed
        ]);

        if ($amount > $maxAllowed) {
            return response()->json([
                'success' => false,
                'error' => "المبلغ ({$amount} دج) يتجاوز الحد الأقصى المسموح ({$maxAllowed} دج)"
            ], 422);
        }

        $payment->update([
            'amount' => $amount,
            'payment_date' => $validated['payment_date'] ?? $payment->payment_date,
            'notes' => $validated['notes'] ?? null
        ]);

        $payment->load('capital');

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث السحب بنجاح',
            'payment' => $payment
        ]);

    } catch (\Exception $e) {
        Log::error('Error updating partner payment: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * ✅ حذف سحب
     */
    public function destroy($id)
    {
        try {
            $payment = PartnerPayment::find($id);
            if (!$payment) {
                return response()->json(['error' => 'السحب غير موجود'], 404);
            }

            $payment->delete();
            return response()->json([
                'success' => true,
                'message' => 'تم حذف السحب بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting partner payment: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}