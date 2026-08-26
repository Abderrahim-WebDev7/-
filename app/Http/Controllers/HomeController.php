<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Worker;
use App\Models\Cost;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index');
    }

    public function stats()
    {
        try {
            $today = now()->toDateString();
            $month = now()->format('Y-m');

            $workers = Worker::count();
            
            // مشتريات اليوم
            $purchasesToday = Transaction::where('date', $today)->where('type', 'purchase')->count();
            $purchasesTotal = Transaction::where('date', $today)->where('type', 'purchase')->sum('total_purchase');
            
            // مبيعات اليوم
            $salesToday = Transaction::where('date', $today)->where('type', 'sale')->count();
            $salesTotal = Transaction::where('date', $today)->where('type', 'sale')->sum('total_sale');
            
            // تكاليف اليوم
            $costsToday = Cost::where('date', $today)->sum('amount');
            
            // أرباح اليوم
            $profitToday = $salesTotal - $purchasesTotal - $costsToday;
            
            // أرباح الشهر
            $monthPurchases = Transaction::where('date', 'like', $month . '%')->where('type', 'purchase')->sum('total_purchase');
            $monthSales = Transaction::where('date', 'like', $month . '%')->where('type', 'sale')->sum('total_sale');
            $monthCosts = Cost::where('date', 'like', $month . '%')->sum('amount');
            $profitMonth = $monthSales - $monthPurchases - $monthCosts;

            return response()->json([
                'workers' => $workers,
                'purchases_today' => $purchasesToday,
                'sales_today' => $salesToday,
                'purchases_total' => $purchasesTotal,
                'sales_total' => $salesTotal,
                'costs_today' => $costsToday,
                'profit_today' => $profitToday,
                'profit_month' => $profitMonth
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}