<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    // جلب جميع المعاملات
    public function index()
    {
        try {
            $transactions = Transaction::orderBy('date', 'desc')->get();
            return response()->json($transactions);
        } catch (\Exception $e) {
            Log::error('Error fetching transactions: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // إضافة معاملة جديدة
    public function store(Request $request)
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

        $plates = $this->getPlates($validated['qty_type'], $validated['qty_count']);
        $totalPurchase = $plates * $validated['purchase_price'];
        $totalSale = $plates * $validated['sale_price'];
        $profit = $totalSale - $totalPurchase;

        $transaction = Transaction::create([
            'date' => $validated['date'],
            'supplier_name' => $validated['supplier_name'],
            'egg_type' => $validated['egg_type'] ?? null,
            'qty_type' => $validated['qty_type'],
            'qty_count' => $validated['qty_count'],
            'purchase_price' => $validated['purchase_price'],
            'buyer_name' => $validated['buyer_name'],
            'sale_price' => $validated['sale_price'],
            'total_purchase' => $totalPurchase,
            'total_sale' => $totalSale,
            'profit' => $profit,
            'notes' => $validated['notes'] ?? null,
            'is_exited' => $validated['is_exited'] ?? false,
            'total_qty' => $validated['qty_count'],
            'taken_qty' => 0,
            'remaining_qty' => $validated['qty_count'],
            'quantity_status' => 'full',
            // أعمدة الدفع الجديدة
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
}

    // حذف معاملة
    public function destroy($id)
    {
        try {
            $transaction = Transaction::find($id);
            if (!$transaction) {
                return response()->json(['error' => 'المعاملة غير موجودة'], 404);
            }

            $transaction->delete();
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المعاملة بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting transaction: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // تأكيد خروج البضاعة
    public function exit($id)
    {
        try {
            $transaction = Transaction::find($id);
            if (!$transaction) {
                return response()->json(['error' => 'المعاملة غير موجودة'], 404);
            }

            $transaction->is_exited = true;
            $transaction->save();

            return response()->json([
                'success' => true,
                'message' => 'تم تأكيد خروج البيع',
                'transaction' => $transaction
            ]);

        } catch (\Exception $e) {
            Log::error('Error exiting transaction: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // تحديث الكمية المأخوذة
    public function update(Request $request, $id)
{
    try {
        Log::info('Updating transaction:', ['id' => $id, 'data' => $request->all()]);
        
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(['error' => 'المعاملة غير موجودة'], 404);
        }

        // تحديث الحقول المسموح بها
        if ($request->has('buyer_paid_amount')) {
            $transaction->buyer_paid_amount = $request->input('buyer_paid_amount');
        }
        if ($request->has('supplier_paid_amount')) {
            $transaction->supplier_paid_amount = $request->input('supplier_paid_amount');
        }
        if ($request->has('notes')) {
            $transaction->notes = $request->input('notes');
        }
        $transaction->save();

        Log::info('Transaction updated:', $transaction->toArray());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث المعاملة بنجاح',
            'transaction' => $transaction
        ]);

    } catch (\Exception $e) {
        Log::error('Error updating transaction: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    // حساب عدد الألواح
    private function getPlates($qtyType, $qtyCount)
    {
        if ($qtyType === 'plate') {
            return $qtyCount;
        } elseif ($qtyType === 'carton12') {
            return $qtyCount * 12;
        }
        return $qtyCount;
    }

    // تأكيد دفع البائع


public function payBuyer($id)
{
    try {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(['error' => 'المعاملة غير موجودة'], 404);
        }

        $transaction->buyer_paid = true;
        $transaction->buyer_paid_date = now()->toDateString();
        $transaction->buyer_paid_amount = $transaction->total_sale;
        $transaction->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تأكيد دفع المشتري',
            'transaction' => $transaction
        ]);

    } catch (\Exception $e) {
        Log::error('Error paying buyer: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function paySupplier($id)
{
    try {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(['error' => 'المعاملة غير موجودة'], 404);
        }

        $transaction->supplier_paid = true;
        $transaction->supplier_paid_date = now()->toDateString();
        $transaction->supplier_paid_amount = $transaction->total_purchase;
        $transaction->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تأكيد دفع البائع',
            'transaction' => $transaction
        ]);

    } catch (\Exception $e) {
        Log::error('Error paying supplier: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function updateTakenQuantity(Request $request, $id)
{
    try {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'error' => 'المعاملة غير موجودة'
            ], 404);
        }

        $takenQty = (int) $request->input('taken_qty', 0);

        $totalQty = (int) (
            $transaction->total_qty ?: $transaction->qty_count
        );

        // منع القيم غير الصحيحة
        if ($takenQty < 0) {
            $takenQty = 0;
        }

        if ($takenQty > $totalQty) {
            $takenQty = $totalQty;
        }

        // حساب الكمية المتبقية
        $remainingQty = $totalQty - $takenQty;

        // تحديد حالة الكمية
        if ($remainingQty <= 0) {
            $status = 'empty';
        } elseif ($takenQty > 0 && $remainingQty > 0) {
            $status = 'partial';
        } else {
            $status = 'full';
        }

        // تحديث المعاملة
        $transaction->taken_qty = $takenQty;
        $transaction->remaining_qty = $remainingQty;
        $transaction->quantity_status = $status;

        $transaction->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الكمية المأخوذة بنجاح',
            'transaction' => $transaction
        ]);

    } catch (\Exception $e) {

        Log::error(
            'Error updating taken quantity: ' . $e->getMessage()
        );

        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}
}