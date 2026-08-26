<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\CostController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CapitalController;


Route::get('/', [HomeController::class, 'index']);

Route::prefix('api')->group(function () {
    // المعاملات
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);
    Route::put('/transactions/{id}/exit', [TransactionController::class, 'exit']);
    Route::put('/transactions/{id}/taken-quantity', [TransactionController::class, 'updateTakenQuantity']);
    Route::put('/transactions/{id}/pay-supplier', [TransactionController::class, 'paySupplier']);
    Route::put('/transactions/{id}/pay-buyer', [TransactionController::class, 'payBuyer']);
    
    // العمّال
    /*
    Route::get('/workers', [WorkerController::class, 'index']);
    Route::post('/workers', [WorkerController::class, 'store']);
    Route::delete('/workers/{id}', [WorkerController::class, 'destroy']);
    Route::post('/workers/{id}/attendance', [WorkerController::class, 'attendance']);
    Route::post('/workers/{id}/pay', [WorkerController::class, 'pay']);*/

    Route::get('/workers', [WorkerController::class, 'index']);
    Route::post('/workers', [WorkerController::class, 'store']);
    Route::delete('/workers/{id}', [WorkerController::class, 'destroy']);
    Route::post('/workers/{id}/attendance', [WorkerController::class, 'attendance']);
    Route::post('/workers/{id}/pay', [WorkerController::class, 'pay']);

    // جلب مدفوعات عامل معين
    Route::get('/payments/worker/{workerId}', [PaymentController::class, 'getWorkerPayments']);
    
    // التكاليف
    Route::get('/costs', [CostController::class, 'index']);
    Route::post('/costs', [CostController::class, 'store']);
    Route::delete('/costs/{id}', [CostController::class, 'destroy']);
    
    // الأشخاص
    Route::get('/people', [PersonController::class, 'index']);
    Route::get('/people/suppliers', [PersonController::class, 'suppliers']);
    Route::get('/people/buyers', [PersonController::class, 'buyers']);
    Route::post('/people', [PersonController::class, 'store']);
    Route::put('/people/{id}', [PersonController::class, 'update']);
    Route::delete('/people/{id}', [PersonController::class, 'destroy']);

    Route::put('/transactions/{id}', [TransactionController::class, 'update']);

    // رأس المال
    Route::get('/capitals', [CapitalController::class, 'index']);
    Route::post('/capitals', [CapitalController::class, 'store']);
    Route::put('/capitals/{id}', [CapitalController::class, 'update']);
    Route::delete('/capitals/{id}', [CapitalController::class, 'destroy']);
    Route::get('/capitals/total', [CapitalController::class, 'total']);

    Route::put('/api/transactions/{id}', [TransactionController::class, 'update']);

    Route::get('/api/people', [PersonController::class, 'index']);

    Route::get('/api/payments', [PaymentController::class, 'index']);

    Route::get('/payments', [PaymentController::class, 'index']);
    Route::get('/payments/month/{month}', [PaymentController::class, 'getWagesByMonth']);

    Route::get('/api/payments/month/{month}', [PaymentController::class, 'getWagesByMonth']);

    Route::get('/attendance', function() {
    return response()->json(\App\Models\Attendance::all());
});
});