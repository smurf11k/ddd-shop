<?php
declare(strict_types=1);

namespace App\Sales\Entity;

use App\Sales\ValueObject\Money;
use App\Sales\ValueObject\ProductDetails;
use App\Sales\ValueObject\ProductId;
use App\Sales\ValueObject\Stock;
use App\Shared\Exception\ValidationException;

final class Product
{
    public function __construct(
        private ProductId $id,
        private ProductDetails $details,
        private Money $price,
        private Stock $stock
    ) {}

    public function id(): ProductId { return $this->id; }
    public function price(): Money { return $this->price; }
    public function stock(): Stock { return $this->stock; }

    public function ensureAvailable(int $qty): void
    {
        if ($qty < 1) throw new ValidationException("Requested qty must be >= 1");
        if (!$this->stock->hasAtLeast($qty)) {
            throw new ValidationException("Product not available in requested quantity.");
        }
    }

    public function updatePrice(Money $newPrice): void
    {
        if ($newPrice->amountCents <= 0) {
            throw new ValidationException("Price must be > 0.");
        }
        $this->price = $newPrice;
    }

    public function decreaseStock(int $qty): void
    {
        $this->stock = $this->stock->decrease($qty);
    }
}
