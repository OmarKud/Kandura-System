<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'order_id' => $this->order_id,
            'total' => (float) $this->total,
            'pdf_url' => $this->pdf_url,
            'created_at' => optional($this->created_at)->toISOString(),

            'order' => $this->whenLoaded('order', function () {

                $order = $this->order;

                return [
                    'id' => $order->id,

                    'user' => $order->relationLoaded('user') && $order->user ? [
                        'id' => $order->user->id,
                        'name' => $order->user->name,
                    ] : null,

                    'address' => $order->relationLoaded('address') && $order->address ? [
                        'id' => $order->address->id,
                        'city' => $order->address->city,
                        'street' => $order->address->street,
                        'build' => $order->address->build,
                    ] : null,

                    'price' => (float) $order->price,
                    'final_price' => (float) ($order->final_price ?? $order->price),
                    'discount' => (float) ($order->price - ($order->final_price ?? $order->price)),

                    'payment_method' => $order->payment_method,
                    'notes' => $order->notes,
                    'status' => $order->status,

                    // items = designOrders
                    'items' => $order->relationLoaded('designOrders')
                        ? $order->designOrders->map(function ($designOrder) {
                            return [
                                'id' => $designOrder->id,

                                'design' => $designOrder->relationLoaded('design') && $designOrder->design
                                    ? [
                                        'id' => $designOrder->design->id,
                                        'name' => $designOrder->design->name ?? null,
                                        'price' => (float) ($designOrder->design->price ?? 0),

                                        'measurement' => $designOrder->relationLoaded('measurement') && $designOrder->measurement
                                            ? [
                                                'id' => $designOrder->measurement->id,
                                                'size' => $designOrder->measurement->size,
                                            ]
                                            : null,
                                    ]
                                    : null,

                                // options: name + type
                                'options' => $designOrder->relationLoaded('options')
                                    ? $designOrder->options->map(fn($opt) => [
                                        'id' => $opt->id,
                                        'name' => $opt->name ?? null,
                                        'type' => $opt->type ?? null,
                                    ])->values()
                                    : [],
                            ];
                        })
                        : [],
                ];
            }),
        ];
    }
}
