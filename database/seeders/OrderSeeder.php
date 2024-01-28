<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Order::factory(20)->create();
    }
}
