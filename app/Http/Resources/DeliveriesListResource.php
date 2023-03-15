<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveriesListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'expected_quantity' => $this->expected_quantity,
            'status' => $this->status,
            'current_quantity' => $this->current_quantity,
        ];
    }
}