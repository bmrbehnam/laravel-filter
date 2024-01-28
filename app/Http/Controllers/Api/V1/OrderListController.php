<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\OrderFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\order\OrderResource;
use App\Models\Order;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderListController extends Controller
{
    /**
     * Show list of orders by applying filters
     *
     * @return AnonymousResourceCollection
     */
    public function __invoke(): AnonymousResourceCollection
    {
        // Simple structure for demo usage of filters
        $orders = Order::query()->with('user')->filter(OrderFilter::class)->latest()->paginate(10);

        return OrderResource::collection($orders);
    }
}
