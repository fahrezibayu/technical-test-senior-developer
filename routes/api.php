<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('throttle:60,1')->group(function () {
    Route::post('transactions', [PaymentController::class, 'createTransaction']);
    Route::post('transactions/{transaction}/process', [PaymentController::class, 'processPayment']);
    Route::get('users/{user}/transactions', [PaymentController::class, 'userTransactions']);
    Route::get('users/{user}/transactions/summary', [PaymentController::class, 'getTransactionSummary']);
});
