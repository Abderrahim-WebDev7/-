<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PersonController extends Controller
{
    // جلب جميع الأشخاص
    public function index()
    {
        try {
            $people = Person::orderBy('full_name', 'asc')->get();
            return response()->json($people);
        } catch (\Exception $e) {
            Log::error('Error fetching people: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // جلب الموردين فقط
    public function suppliers()
    {
        try {
            $suppliers = Person::suppliers()->orderBy('full_name', 'asc')->get();
            return response()->json($suppliers);
        } catch (\Exception $e) {
            Log::error('Error fetching suppliers: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // جلب المشترين فقط
    public function buyers()
    {
        try {
            $buyers = Person::buyers()->orderBy('full_name', 'asc')->get();
            return response()->json($buyers);
        } catch (\Exception $e) {
            Log::error('Error fetching buyers: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // إضافة شخص جديد
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
                'type' => 'required|in:supplier,buyer,both',
                'notes' => 'nullable|string'
            ]);

            $person = Person::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'تمت إضافة الشخص بنجاح',
                'person' => $person
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating person: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // تحديث شخص
    public function update(Request $request, $id)
    {
        try {
            $person = Person::find($id);
            if (!$person) {
                return response()->json(['error' => 'الشخص غير موجود'], 404);
            }

            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
                'type' => 'required|in:supplier,buyer,both',
                'notes' => 'nullable|string'
            ]);

            $person->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الشخص بنجاح',
                'person' => $person
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating person: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // حذف شخص
    public function destroy($id)
    {
        try {
            $person = Person::find($id);
            if (!$person) {
                return response()->json(['error' => 'الشخص غير موجود'], 404);
            }

            $person->delete();
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الشخص بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting person: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}