<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'status' => fake()->randomElement(OrderStatus::values()),
            'nation_code' => fake()->numerify('##########'),
            'amount' => fake()->numberBetween(1, 1000),
            'user_id' => User::query()->inRandomOrder()->first()->id ?? User::factory()
        ];
    }


    public function userId($id): self
    {
        return $this->state(fn($attributes) => [
            'user_id' => $id,
        ]);
    }
}
