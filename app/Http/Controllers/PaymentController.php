<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function index()
    {
        try {
            $payments = Payment::all();
            return response()->json($payments);
        } catch (\Exception $e) {
            Log::error('Error fetching payments: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getWorkerPayments($workerId)
    {
        try {
            $payments = Payment::where('worker_id', $workerId)->get();
            return response()->json($payments);
        } catch (\Exception $e) {
            Log::error('Error fetching worker payments: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}