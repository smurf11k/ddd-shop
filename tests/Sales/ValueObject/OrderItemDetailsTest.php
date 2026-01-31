<?php
declare(strict_types=1);

namespace Tests\Sales\ValueObject;

use App\Sales\ValueObject\Money;
use App\Sales\ValueObject\OrderItemDetails;
use App\Sales\ValueObject\ProductId;
use App\Shared\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

final class OrderItemDetailsTest extends TestCase
{
    public function testTotalIsPriceTimesQuantity(): void
    {
        $pid = ProductId::new();
        $price = new Money('USD', 2500); // $25.00
        $item = new OrderItemDetails($pid, 3, $price);

        $total = $item->total();

        $this->assertSame('USD', $total->currency);
        $this->assertSame(7500, $total->amountCents);
    }

    public function testQuantityMustBeAtLeastOne(): void
    {
        $this->expectException(ValidationException::class);

        $pid = ProductId::new();
        $price = new Money('USD', 1000);
        new OrderItemDetails($pid, 0, $price);
    }
}
