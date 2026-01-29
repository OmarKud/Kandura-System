<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $address = [
            'id' => $this->id,
            "city" => $this->city,
            "street" => $this->street,
            "build" => $this->build,
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
            'created_at' => $this->created_at,
        ];

        return $address;
    }
}
