<?php
declare(strict_types=1);

namespace App\Sales\Entity;

use App\Sales\ValueObject\Address;
use App\Sales\ValueObject\Email;
use App\Sales\ValueObject\Name;
use App\Shared\ValueObject\Uuid;

final class Customer
{
    /** @var Order[] */
    private array $orders = [];

    public function __construct(
        private Uuid $id,
        private Name $name,
        private Email $email,
        private Address $address
    ) {}

    public function id(): Uuid { return $this->id; }
    public function address(): Address { return $this->address; }

    public function addOrder(Order $order): void
    {
        $this->orders[] = $order;
    }

    public function hasActiveOrders(): bool
    {
        foreach ($this->orders as $o) {
            if (!$o->isCompleted()) return true;
        }
        return false;
    }

    public function changeShippingAddress(Address $newAddress): void
    {
        if ($this->hasActiveOrders()) {
            throw new \App\Shared\Exception\ValidationException("Cannot change customer address while active orders exist.");
        }
        $this->address = $newAddress;
    }
}
