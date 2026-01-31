<?php
declare(strict_types=1);

namespace App\Sales\ValueObject;

use App\Shared\Exception\ValidationException;

final readonly class Stock
{
    public function __construct(public int $quantity)
    {
        if ($quantity < 0) {
            throw new ValidationException("Stock cannot be negative.");
        }
    }

    public function decrease(int $by): self
    {
        if ($by <= 0) {
            throw new ValidationException("Decrease value must be > 0.");
        }
        if ($this->quantity < $by) {
            throw new ValidationException("Not enough stock.");
        }
        return new self($this->quantity - $by);
    }

    public function hasAtLeast(int $need): bool
    {
        return $this->quantity >= $need;
    }
}
