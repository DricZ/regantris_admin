<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'hotel_id'         => $this->hotel_id,
            'type'         => $this->type,
            'title'      => $this->title,
            'desc'=> $this->desc,
            'image_url'  => $this->image_url,   // dari accessor
            'price'  => $this->price,   // dari accessor
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
            // ... atribut lain
        ];
    }
}
