<?php
declare(strict_types=1);

namespace App\Sales\ValueObject;

use App\Shared\Exception\ValidationException;

enum OrderStatus: string
{
    case NEW = 'NEW';
    case CONFIRMED = 'CONFIRMED';
    case SHIPPED = 'SHIPPED';
    case DELIVERED = 'DELIVERED';

    public function canTransitionTo(self $next): bool
    {
        return match ($this) {
            self::NEW => in_array($next, [self::CONFIRMED], true),
            self::CONFIRMED => in_array($next, [self::SHIPPED], true),
            self::SHIPPED => in_array($next, [self::DELIVERED], true),
            self::DELIVERED => false,
        };
    }

    public function assertTransition(self $next): void
    {
        if (!$this->canTransitionTo($next)) {
            throw new ValidationException("Invalid status transition {$this->value} -> {$next->value}");
        }
    }
}
