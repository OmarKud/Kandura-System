<?php

namespace App\Service;

class NotificationActionResolver
{
    public function resolve(?array $action): ?string
    {
        if (!$action || empty($action['type'])) {
            return null;
        }

        $type = $action['type'];
        $params = $action['params'] ?? [];

        return match ($type) {
            // Orders
           'go_to_order_list' => route('dashboard.orders.index'),
            'go_to_order_details' => isset($params['order_id'])
                ? route('dashboard.orders.show', $params['order_id'])
                : null,

            // Designs (Dashboard)
            'go_to_design_list' => route('dashboard.designs.index'),
            'go_to_design_details' => isset($params['design_id'])
                ? route('dashboard.designs.show', $params['design_id'])
                : null,

            default => null,
        };
    }
}
