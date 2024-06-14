<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
