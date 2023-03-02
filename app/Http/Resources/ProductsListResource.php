<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductsListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'product_line' => $this->product_line,
            'quantity' => $this->quantity,
            'cost' => $this->cost,
            'quantity_sold' => $this->quantity_sold,
        ];
    }
}
