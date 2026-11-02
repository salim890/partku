<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case PACKED = 'packed';
    case SHIPPED = 'shipped';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    // Aturan Transisi (State Machine)
    public function canTransitionTo(self $newStatus): bool
    {
        $allowedTransitions = [
            'pending'   => ['paid', 'cancelled'],
            'paid'      => ['packed', 'cancelled'],
            'packed'    => ['shipped'],
            'shipped'   => ['completed'],
            'completed' => [],
            'cancelled' => [],
        ];

        return in_array($newStatus->value, $allowedTransitions[$this->value] ?? []);
    }
}