<?php
declare(strict_types=1);

namespace Tests\Sales\Entity;

use App\Sales\Entity\Product;
use App\Sales\ValueObject\Dimensions;
use App\Sales\ValueObject\Money;
use App\Sales\ValueObject\ProductDetails;
use App\Sales\ValueObject\ProductId;
use App\Sales\ValueObject\Stock;
use App\Shared\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase
{
    private function makeProduct(int $stockQty = 5): Product
    {
        return new Product(
            ProductId::new(),
            new ProductDetails('Phone', 'Nice phone', new Dimensions(10, 5, 1)),
            new Money('USD', 9999),
            new Stock($stockQty)
        );
    }

    public function testEnsureAvailableThrowsWhenNotEnoughStock(): void
    {
        $p = $this->makeProduct(2);

        $this->expectException(ValidationException::class);
        $p->ensureAvailable(3);
    }

    public function testDecreaseStockReducesQuantity(): void
    {
        $p = $this->makeProduct(5);

        $p->decreaseStock(2);

        $this->assertSame(3, $p->stock()->quantity);
    }

    public function testUpdatePriceRejectsZeroOrNegative(): void
    {
        $p = $this->makeProduct(5);

        $this->expectException(ValidationException::class);
        $p->updatePrice(new Money('USD', 0));
    }
}
