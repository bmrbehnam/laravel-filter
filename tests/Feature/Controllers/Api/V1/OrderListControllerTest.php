<?php

namespace Tests\Feature\Controllers\Api\V1;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class OrderListControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_orders_list_without_filters(): void
    {
        $user = User::factory()->create();
        $orders = Order::factory()->userId($user->id)->count(5)->create();

        // without authentication for this task
        $response = $this->getJson(route('api.v1.orders.index'));

        $response->assertStatus(ResponseAlias::HTTP_OK);
        $data = [];
        foreach ($orders as $order) {
            $data [] = [
                'id' => $order->id,
                'user_id' => $order->user_id,
                'name' => $user->name,
                'status' => $order->status,
                'amount' => $order->amount
            ];
        }
        $response->assertExactJson([
            'data' => $data,
            'pagination' => [
                'current_page' => 1,
                'per_page' => 10,
                'total' => count($orders),
            ],
        ]);
    }

    public function test_user_can_get_orders_list_with_status_filter(): void
    {
        $user = User::factory()->create();

        Order::factory()->userId($user->id)->create(['status' => OrderStatus::PENDING]);
        $order = Order::factory()->userId($user->id)->create(['status' => OrderStatus::PAID]);
        Order::factory()->userId($user->id)->create(['status' => OrderStatus::REJECTED]);
        Order::factory()->userId($user->id)->create(['status' => OrderStatus::COMPLETED]);

        $response = $this->getJson(route('api.v1.orders.index', ['status' => OrderStatus::PAID]));

        $response->assertStatus(ResponseAlias::HTTP_OK);
        $response->assertExactJson([
            'data' => [
                [
                    'id' => $order->id,
                    'user_id' => $order->user_id,
                    'name' => $user->name,
                    'status' => $order->status,
                    'amount' => $order->amount
                ]
            ],
            'pagination' => [
                'current_page' => 1,
                'per_page' => 10,
                'total' => 1,
            ],
        ]);
    }

    public function test_user_can_get_orders_list_with_amount_filters(): void
    {
        $user = User::factory()->create();

        Order::factory()->userId($user->id)->create(['amount' => 10]);
        Order::factory()->userId($user->id)->create(['amount' => 60]);
        $order1 = Order::factory()->userId($user->id)->create(['amount' => 25]);
        $order2 = Order::factory()->userId($user->id)->create(['amount' => 30]);

        $response = $this->getJson(route('api.v1.orders.index', ['min_amount' => 20, 'max_amount' => 30]));

        $response->assertStatus(ResponseAlias::HTTP_OK);
        $response->assertExactJson([
            'data' => [
                [
                    'id' => $order1->id,
                    'user_id' => $order1->user_id,
                    'name' => $user->name,
                    'status' => $order1->status,
                    'amount' => $order1->amount
                ],
                [
                    'id' => $order2->id,
                    'user_id' => $order2->user_id,
                    'name' => $user->name,
                    'status' => $order2->status,
                    'amount' => $order2->amount
                ]
            ],
            'pagination' => [
                'current_page' => 1,
                'per_page' => 10,
                'total' => 2,
            ],
        ]);
    }

    public function test_user_can_get_orders_list_with_nation_code_filter(): void
    {
        $user = User::factory()->create();

        Order::factory()->userId($user->id)->create(['nation_code' => '20102020']);
        $order = Order::factory()->userId($user->id)->create(['nation_code' => '10103698989']);
        Order::factory()->userId($user->id)->create(['nation_code' => '23254546']);
        Order::factory()->userId($user->id)->create(['nation_code' => '454654745']);

        $response = $this->getJson(route('api.v1.orders.index', ['nation_code' => '1010']));

        $response->assertStatus(ResponseAlias::HTTP_OK);
        $response->assertExactJson([
            'data' => [
                [
                    'id' => $order->id,
                    'user_id' => $order->user_id,
                    'name' => $user->name,
                    'status' => $order->status,
                    'amount' => $order->amount
                ]
            ],
            'pagination' => [
                'current_page' => 1,
                'per_page' => 10,
                'total' => 1,
            ],
        ]);
    }

    public function test_user_can_get_orders_list_with_user_filter(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Order::factory()->userId($user1->id)->create();
        $order = Order::factory()->userId($user2->id)->create();
        Order::factory()->userId($user1->id)->create();
        Order::factory()->userId($user1->id)->create();

        $response = $this->getJson(route('api.v1.orders.index', ['user' => $user2->id]));

        $response->assertStatus(ResponseAlias::HTTP_OK);
        $response->assertExactJson([
            'data' => [
                [
                    'id' => $order->id,
                    'user_id' => $order->user_id,
                    'name' => $user2->name,
                    'status' => $order->status,
                    'amount' => $order->amount
                ]
            ],
            'pagination' => [
                'current_page' => 1,
                'per_page' => 10,
                'total' => 1,
            ],
        ]);
    }

    public function test_user_can_get_orders_list_with_customer_name_filter(): void
    {
        $user1 = User::factory()->create(['name' => 'david']);
        $user2 = User::factory()->create(['name' => 'bob']);

        Order::factory()->userId($user1->id)->create();
        $order = Order::factory()->userId($user2->id)->create();
        Order::factory()->userId($user1->id)->create();
        Order::factory()->userId($user1->id)->create();

        $response = $this->getJson(route('api.v1.orders.index', ['customer_name' => 'bo']));

        $response->assertStatus(ResponseAlias::HTTP_OK);
        $response->assertExactJson([
            'data' => [
                [
                    'id' => $order->id,
                    'user_id' => $order->user_id,
                    'name' => $user2->name,
                    'status' => $order->status,
                    'amount' => $order->amount
                ]
            ],
            'pagination' => [
                'current_page' => 1,
                'per_page' => 10,
                'total' => 1,
            ],
        ]);
    }
}
