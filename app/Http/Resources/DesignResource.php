<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'status' => $this->status,

            'measurements' => $this->whenLoaded('measurements', function () {
    return $this->measurements->map(fn($m) => [
        'id' => $m->id,
        'size' => $m->size,
    ]);
}),


'images' => ImageResource::collection(
    $this->whenLoaded('images')
),


            'options' => DesignOptionSelectionResource::collection(
                $this->whenLoaded('optionSelections')
            ),

            'created_at' => $this->created_at,
        ];
    }
}
