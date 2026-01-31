<?php
declare(strict_types=1);

namespace App\Sales\Entity;

use App\Sales\ValueObject\Address;
use App\Sales\ValueObject\Money;
use App\Sales\ValueObject\OrderItemDetails;
use App\Sales\ValueObject\OrderStatus;
use App\Shared\Exception\ValidationException;
use App\Shared\ValueObject\Uuid;

final class Order
{
    /** @var OrderItemDetails[] */
    private array $items = [];

    private Money $totalPrice;
    private OrderStatus $status;

    public function __construct(
        private Uuid $id,
        private Customer $customer,
        private Address $shippingAddress,
        Money $zeroMoney
    ) {
        $this->status = OrderStatus::NEW;
        $this->totalPrice = $zeroMoney;
    }

    public function status(): OrderStatus { return $this->status; }
    public function totalPrice(): Money { return $this->totalPrice; }

    public function isCompleted(): bool
    {
        return $this->status === OrderStatus::DELIVERED;
    }

    public function addItem(OrderItemDetails $item): void
    {
        $this->assertMutable();
        $this->items[] = $item;
        $this->recalculateTotal();
    }

    public function removeItemByProductId(string $productId): void
    {
        $this->assertMutable();
        $this->items = array_values(array_filter(
            $this->items,
            fn(OrderItemDetails $i) => $i->productId->toString() !== $productId
        ));
        $this->recalculateTotal();
    }

    public function changeStatus(OrderStatus $next): void
    {
        $this->status->assertTransition($next);
        $this->status = $next;
    }

    public function changeShippingAddress(Address $newAddress): void
    {
        $this->assertMutable();
        $this->shippingAddress = $newAddress;
    }

    private function recalculateTotal(): void
    {
        if (count($this->items) === 0) {
            $this->totalPrice = new Money($this->totalPrice->currency, 0);
            return;
        }

        $currency = $this->items[0]->price->currency;
        $sum = new Money($currency, 0);

        foreach ($this->items as $i) {
            if ($i->price->currency !== $currency) {
                throw new ValidationException("All order items must have the same currency.");
            }
            $sum = $sum->add($i->total());
        }

        $this->totalPrice = $sum;
    }

    private function assertMutable(): void
    {
        if ($this->status === OrderStatus::SHIPPED || $this->status === OrderStatus::DELIVERED) {
            throw new ValidationException("Order cannot be modified when status is {$this->status->value}");
        }
    }
}
