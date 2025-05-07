<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromotionalResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'voucher_id'         => $this->voucher_id,
            'name'      => $this->name,
            'desc'=> $this->desc,
            'image_url'  => $this->image_url,   // dari accessor
            'price'  => $this->price,   // dari accessor
            // ... atribut lain
        ];
    }
}
