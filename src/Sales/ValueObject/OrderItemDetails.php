<?php
declare(strict_types=1);

namespace App\Sales\ValueObject;

use App\Shared\Exception\ValidationException;

final readonly class OrderItemDetails
{
    public function __construct(
        public ProductId $productId,
        public int $quantity,
        public Money $price
    ) {
        if ($quantity < 1) {
            throw new ValidationException("Quantity must be at least 1.");
        }
    }

    public function total(): Money
    {
        return new Money($this->price->currency, $this->price->amountCents * $this->quantity);
    }
}
