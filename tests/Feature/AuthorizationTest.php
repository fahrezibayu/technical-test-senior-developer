<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authorized_access_to_own_transactions()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->get("/api/users/{$user->id}/transactions");

        $response->assertStatus(200); // Harusnya mendapatkan akses 200 OK karena pemilik akun
    }
}
