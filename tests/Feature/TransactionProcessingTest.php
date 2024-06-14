<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use App\Jobs\ProcessTransaction;

class TransactionProcessingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_process_transaction_using_queue()
    {
        // Disable Laravel's automatic handling of jobs during tests
        Queue::fake();

        // Create a user
        $user = User::factory()->create();

        // Create a transaction
        $transaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
        ]);

        // Dispatch the job to process the transaction
        ProcessTransaction::dispatch($transaction);

        // Assert that the job was pushed to the queue
        Queue::assertPushed(ProcessTransaction::class, function ($job) use ($transaction) {
            // Use the getter method provided by Laravel for protected properties
            return $job->getTransaction()->id === $transaction->id;
        });

        // Retrieve the processed transaction from the database
        $processedTransaction = $transaction->refresh();

        // Assert that the transaction status is now 'completed'
        $this->assertEquals('completed', $processedTransaction->status);
    }
}
