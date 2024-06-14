<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PaymentController extends Controller
{
    public function createTransaction(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            'amount' => $request->input('amount'),
            'status' => $request->input('status'),
        ]);

        return response()->json($transaction, 201);
    }

    public function processPayment(Request $request, $transactionId)
    {
        $transaction = Transaction::find($transactionId);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 200);
        }

        $transaction->status = 'completed';
        $transaction->save();

        return response()->json($transaction);
    }

    public function getTransactionHistory(Request $request)
    {
        $userId = $request->user()->id;
        $page = $request->query('page', 1);
        $cacheKey = "user:{$userId}:transactions:page:{$page}";

        $transactions = cache()->remember($cacheKey, 60, function () use ($userId) {
            return Transaction::where('user_id', $userId)
                ->paginate(10);
        });

        return response()->json($transactions);
    }

    public function getTransactionSummary(Request $request)
    {
        $summary = DB::table('transactions')
            ->select(DB::raw('status, COUNT(*) as count, SUM(amount) as total'))
            ->groupBy('status')
            ->get();

        return response()->json($summary);
    }

    public function userTransactions($userId)
    {
        $page = request()->get('page', 1);
        $cacheKey = "user:{$userId}:transactions:page:{$page}";

        $transactions = Cache::tags("user:{$userId}:transactions")->remember($cacheKey, 600, function () use ($userId) {
            return Transaction::where('user_id', $userId)->paginate(10);
        });

        return response()->json($transactions);
    }
}

