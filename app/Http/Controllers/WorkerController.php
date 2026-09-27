<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\Payment;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;

class WorkerController extends Controller
{
    public function index()
    {
        try {
            $workers = Worker::with('payments')->get();
            return response()->json($workers);
        } catch (\Exception $e) {
            Log::error('Error fetching workers: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

/*    public function store(Request $request)
{
    try {
        Log::info('Transaction data received:', $request->all());

        $validated = $request->validate([
            'date' => 'required|date',
            'supplier_name' => 'required|string|max:255',
            'egg_type' => 'nullable|string|max:100',
            'qty_type' => 'required|string|in:plate,carton12',
            'qty_count' => 'required|integer|min:1',
            'purchase_price' => 'required|numeric|min:0',
            'buyer_name' => 'required|string|max:255',
            'sale_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'is_exited' => 'nullable|boolean',
            'supplier_paid' => 'nullable|boolean',
            'buyer_paid' => 'nullable|boolean'
        ]);

        // ✅ حساب عدد الألواح
        $qtyType = $validated['qty_type'];
        $qtyCount = $validated['qty_count'];
        $plates = ($qtyType === 'carton12') ? $qtyCount * 12 : $qtyCount;

        // ✅ حساب الإجماليات
        $totalPurchase = $plates * $validated['purchase_price'];
        $totalSale = $plates * $validated['sale_price'];
        $profit = $totalSale - $totalPurchase;

        Log::info('Calculated:', [
            'plates' => $plates,
            'total_purchase' => $totalPurchase,
            'total_sale' => $totalSale,
            'profit' => $profit
        ]);

        $transaction = Transaction::create([
            'date' => $validated['date'],
            'supplier_name' => $validated['supplier_name'],
            'egg_type' => $validated['egg_type'] ?? null,
            'qty_type' => $qtyType,
            'qty_count' => $qtyCount,
            'purchase_price' => $validated['purchase_price'],
            'buyer_name' => $validated['buyer_name'],
            'sale_price' => $validated['sale_price'],
            'total_purchase' => $totalPurchase,
            'total_sale' => $totalSale,
            'profit' => $profit,
            'notes' => $validated['notes'] ?? null,
            'is_exited' => $validated['is_exited'] ?? false,
            'total_qty' => $qtyCount,
            'taken_qty' => 0,
            'remaining_qty' => $qtyCount,
            'quantity_status' => 'full',
            'supplier_paid' => $validated['supplier_paid'] ?? false,
            'buyer_paid' => $validated['buyer_paid'] ?? false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة المعاملة بنجاح',
            'transaction' => $transaction
        ], 201);

    } catch (\Exception $e) {
        Log::error('Error creating transaction: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}*/

public function store(Request $request)
{
    try {
        Log::info('Worker data received:', $request->all());

        // ✅ التحقق من حقول العامل وليس المعاملة
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'wage' => 'required|numeric|min:0'
        ]);

        // ✅ إنشاء عامل جديد (وليس معاملة)
        $worker = Worker::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'wage' => $validated['wage'],
            'present_days' => 0,
            'absent_days' => 0,
            'rest_days' => 0,
            'total_salary' => 0,
            'remaining_salary' => 0,
            'current_status' => 'pending'
        ]);

        Log::info('Worker created:', $worker->toArray());

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة العامل بنجاح',
            'worker' => $worker
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // ✅ إرجاع أخطاء التحقق بشكل واضح
        return response()->json([
            'success' => false,
            'error' => $e->validator->errors()->first(),
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Error creating worker: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function destroy($id)
    {
        try {
            $worker = Worker::find($id);
            if ($worker) {
                $worker->delete();
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error deleting worker: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function attendance(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:present,absent,rest'
            ]);

            $worker = Worker::with('payments')->find($id);
            if (!$worker) {
                return response()->json(['error' => 'العامل غير موجود'], 404);
            }

            $updated = $worker->markAttendance($request->status);
            $worker->refresh()->load('payments');

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الحضور',
                'worker' => $worker,
                'changed' => $updated
            ]);

        } catch (\Exception $e) {
            Log::error('Attendance error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function pay(Request $request, $id)
    {
        try {
            Log::info('Pay request received:', ['worker_id' => $id, 'data' => $request->all()]);

            $worker = Worker::with('payments')->find($id);
            if (!$worker) {
                return response()->json(['error' => 'العامل غير موجود'], 404);
            }

            $amount = (float) $request->input('amount', 0);
            $type = $request->input('type', 'full');

            $worker->recalculateSalary();
            $totalSalary = (float) $worker->total_salary;
            $paidAmount = (float) $worker->paid_amount;
            $remaining = round($totalSalary - $paidAmount, 2);

            if ($remaining <= 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'لا يوجد مبلغ متبقي للدفع',
                    'worker' => $worker,
                    'deducted' => 0,
                    'remaining' => 0,
                    'total_paid' => $paidAmount,
                    'total_salary' => $totalSalary
                ], 422);
            }

            $paymentType = $type;
            if ($type === 'full' || $amount <= 0 || $amount >= $remaining) {
                $deductedAmount = $remaining;
                $paymentType = 'full';
            } else {
                $deductedAmount = round(min($amount, $remaining), 2);
                $paymentType = 'partial';
            }

            $newRemaining = round($remaining - $deductedAmount, 2);

            $payment = DB::transaction(function () use ($worker, $deductedAmount, $paymentType, $newRemaining) {
                $payment = Payment::create([
                    'worker_id' => $worker->id,
                    'date' => now()->toDateString(),
                    'amount' => $deductedAmount,
                    'type' => $paymentType,
                    'remaining_after' => $newRemaining,
                    'notes' => $paymentType === 'full'
                        ? 'دفع كامل الراتب'
                        : "دفع جزئي: {$deductedAmount} دج، المتبقي: {$newRemaining} دج"
                ]);

                $worker->unsetRelation('payments');
                $worker->load('payments');
                $worker->recalculateSalary();
                $worker->save();

                return $payment;
            });

            $worker->refresh()->load('payments');

            Log::info('Payment recorded:', $payment->toArray());

            return response()->json([
                'success' => true,
                'message' => $paymentType === 'full' ? 'تم دفع كامل الراتب بنجاح' : 'تم خصم المبلغ بنجاح',
                'worker' => $worker,
                'payment' => $payment,
                'deducted' => $deductedAmount,
                'remaining' => (float) $worker->remaining_salary,
                'total_paid' => (float) $worker->paid_amount,
                'total_salary' => (float) $worker->total_salary
            ]);

        } catch (\Exception $e) {
            Log::error('Pay error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
