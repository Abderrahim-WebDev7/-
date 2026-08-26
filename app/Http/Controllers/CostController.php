<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CostController extends Controller
{
    // جلب جميع التكاليف
    public function index()
    {
        try {
            $costs = Cost::orderBy('date', 'desc')->get();
            return response()->json($costs);
        } catch (\Exception $e) {
            Log::error('Error fetching costs: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // إضافة تكلفة جديدة
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'date' => 'required|date',
                'reason' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0'
            ]);

            $cost = Cost::create($validated);

            return response()->json($cost, 201);
            
        } catch (\Exception $e) {
            Log::error('Error creating cost: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // حذف تكلفة
    public function destroy($id)
    {
        try {
            Cost::where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error deleting cost: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}