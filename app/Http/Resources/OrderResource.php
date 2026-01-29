<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user', fn() => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? null,
            ]),
            'address' => $this->whenLoaded('address', fn() => [
                'id' => $this->address->id ?? null,
                'city' => $this->address->city ?? null,
                'street' => $this->address->street ?? null,
                'build' => $this->address->build ?? null,

            ]),

            'price' => (float) $this->price,
            'final_price' => (float) ($this->final_price ?? $this->price),
            'discount' => (float) ($this->price - ($this->final_price ?? $this->price)),

            'payment_method' => $this->payment_method,
            'notes' => $this->notes,
            "status" => $this->status,




            'items' => $this->whenLoaded('designOrders', function () {
                return $this->designOrders->map(function ($designOrder) {
                    return [

                        'design' => $designOrder->relationLoaded('design') && $designOrder->design
                            ? [
                                'id' => $designOrder->design->id,
                                'name' => $designOrder->design->name ?? null,
                                'price' => (float) ($designOrder->design->price ?? 0),
                                'measurement' => $designOrder->relationLoaded('measurement') && $designOrder->measurement ? [
                                    'id' => $designOrder->measurement->id,
                                    'size' => $designOrder->measurement->size,
                                ] : null,
                            ]
                            : null,

                        'options' => $designOrder->relationLoaded('options')
                            ? $designOrder->options->map(fn($opt) => [
                                'id' => $opt->id,
                                'name' => $opt->name ?? null,
                            ])
                            : [],
                    ];
                });
            }),
            'checkout_url' => $this->when(!empty($this->checkout_url), $this->checkout_url),
        ];
    }
}
