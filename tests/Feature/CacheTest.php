<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;

class CacheTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_cache_transaction_summary()
    {
        // Membuat user baru
        $user = User::factory()->create();

        // Mengaktifkan autentikasi sebagai user menggunakan Passport
        Passport::actingAs($user);

        // Memanggil endpoint untuk pertama kali
        $firstResponse = $this->get("/api/users/{$user->id}/transactions/summary");
        $firstResponse->assertStatus(200);

        // Mendapatkan data dari respons pertama
        $firstData = $firstResponse->json();

        // Memanggil endpoint kembali untuk memastikan data diambil dari cache
        $secondResponse = $this->get("/api/users/{$user->id}/transactions/summary");
        $secondResponse->assertStatus(200);

        // Memeriksa apakah data dari respons kedua sama dengan data dari respons pertama
        $this->assertEquals($firstData, $secondResponse->json());
    }
}
