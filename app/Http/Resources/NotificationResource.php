<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        $createdAt = $this->created_at;

        return [
             'id' => $this->id,
            'is_read' => !is_null($this->read_at),
            'read_at' => $this->read_at?->format('H:i'),

            'date' => $createdAt?->toDateString(),
            'time' => $createdAt?->format('H:i'),

            'type' => $this->data['type'] ?? null,
            'title' => $this->data['title'] ?? null,
            'body'  => $this->data['body'] ?? null,
            

            'created_at' => $createdAt?->toISOString(),
        ];
    }
}
