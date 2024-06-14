<?php

namespace App\Jobs;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ProcessTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function handle()
    {
        // Process the transaction
        $this->transaction->status = 'completed';
        $this->transaction->save();

        // Clear cache after processing transaction
        $userId = $this->transaction->user_id;
        Cache::tags("user:{$userId}:transactions")->flush();
    }

    // Getter method for accessing $transaction
    public function getTransaction()
    {
        return $this->transaction;
    }
}
