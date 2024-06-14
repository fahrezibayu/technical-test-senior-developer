<?php

// tests\Unit\Models\TransactionTest.php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_belongs_to_user()
    {
        // Membuat user
        $user = User::factory()->create();

        // Membuat transaction yang terkait dengan user
        $transaction = Transaction::factory()->create([
            'user_id' => $user->id,
        ]);

        // Memastikan transaction terkait dengan user
        $this->assertInstanceOf(User::class, $transaction->user);
        $this->assertEquals($user->id, $transaction->user->id);
    }
}
