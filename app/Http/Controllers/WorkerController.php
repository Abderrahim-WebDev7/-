<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'wage' => 'required|numeric|min:0'
            ]);

            $worker = Worker::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? null,
                'wage' => $validated['wage'],
                'present_days' => 0,
                'absent_days' => 0,
                'rest_days' => 0,
                'total_salary' => 0,
                'remaining_salary' => 0,
                'current_status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة العامل بنجاح',
                'worker' => $worker
            ], 201);
            
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

            $worker = Worker::find($id);
            if (!$worker) {
                return response()->json(['error' => 'العامل غير موجود'], 404);
            }

            $updated = $worker->markAttendance($request->status);
            
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الحضور',
                'worker' => $worker,
                'changed' => $updated
            ], 201);
            
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
            
            $worker = Worker::find($id);
            if (!$worker) {
                return response()->json(['error' => 'العامل غير موجود'], 404);
            }

            $amount = $request->input('amount', 0);
            $type = $request->input('type', 'full');

            $totalSalary = $worker->total_salary;
            $paidAmount = $worker->paid_amount;
            $remaining = $totalSalary - $paidAmount;
            
            $deductedAmount = 0;
            $paymentType = $type;
            
            if ($type === 'full' || $amount <= 0 || $amount >= $remaining) {
                $deductedAmount = $remaining;
                $paymentType = 'full';
            } else {
                $deductedAmount = min($amount, $remaining);
                $paymentType = 'partial';
            }
            
            $newRemaining = $remaining - $deductedAmount;
            
            if ($deductedAmount > 0) {
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
                
                Log::info('Payment recorded:', $payment->toArray());
            }

            return response()->json([
                'success' => true,
                'message' => $paymentType === 'full' ? 'تم دفع كامل الراتب بنجاح' : 'تم خصم المبلغ بنجاح',
                'worker' => $worker,
                'payment' => $payment ?? null,
                'deducted' => $deductedAmount,
                'remaining' => $newRemaining,
                'total_paid' => $paidAmount + $deductedAmount,
                'total_salary' => $totalSalary
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Pay error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}