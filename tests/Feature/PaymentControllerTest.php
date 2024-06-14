<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\Passport;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_transaction()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/transactions', [
                'amount' => 100.0,
                'status' => 'pending',
            ]);

        $response->assertStatus(201); // Memastikan transaksi berhasil dibuat
        $this->assertDatabaseHas('transactions', ['amount' => 100.0]);
    }

    /** @test */
    public function can_process_payment()
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->postJson("/api/transactions/{$transaction->id}/process");

        $response->assertStatus(200); // Memastikan pembayaran berhasil diproses
        $this->assertEquals('completed', $transaction->refresh()->status);
    }

    /** @test */
    public function can_access_user_transactions()
    {
        $user = User::factory()->create();

        // Acting as user without creating fresh API token
        Passport::actingAs($user);

        $response = $this->get("/api/users/{$user->id}/transactions");
        $response->assertStatus(200);
    }

    /** @test */
    public function can_get_transaction_summary()
    {
        // Membuat user baru
        $user = User::factory()->create();

        // Mengaktifkan autentikasi sebagai user
        Passport::actingAs($user);

        // Memanggil endpoint untuk mendapatkan summary transaksi
        $response = $this->get("/api/users/{$user->id}/transactions/summary");

        // Memastikan respons memiliki status 200
        $response->assertStatus(200);
    }
}
