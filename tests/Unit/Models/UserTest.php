<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCreation()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->assertNotNull($user);
        $this->assertEquals('John Doe', $user->name);
    }

    // tambahkan pengujian lainnya sesuai kebutuhan
}

