<?php

namespace App\Http\Resources\V1\order;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->user->name,
            'status' => $this->status,
            'amount' => $this->amount
        ];
    }
}
