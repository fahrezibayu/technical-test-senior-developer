<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\Passport;

class ThrottleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function throttle_limit_exceeded()
    {
        // Membuat user baru
        $user = User::factory()->create();

        // Mengaktifkan autentikasi sebagai user menggunakan Passport
        Passport::actingAs($user);

        // Memanggil endpoint beberapa kali untuk melampaui batas throttle
        for ($i = 0; $i < 61; $i++) {
            $response = $this->get("/api/users/{$user->id}/transactions/summary");
        }

        // Memastikan respons terakhir mengembalikan status 429 (Too Many Requests)
        $response->assertStatus(429);
    }
}
