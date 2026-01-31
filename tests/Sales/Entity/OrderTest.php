<?php
declare(strict_types=1);

namespace Tests\Sales\Entity;

use App\Sales\Entity\Customer;
use App\Sales\Entity\Order;
use App\Sales\ValueObject\Address;
use App\Sales\ValueObject\Email;
use App\Sales\ValueObject\Money;
use App\Sales\ValueObject\Name;
use App\Sales\ValueObject\OrderItemDetails;
use App\Sales\ValueObject\OrderStatus;
use App\Sales\ValueObject\ProductId;
use App\Shared\Exception\ValidationException;
use App\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

final class OrderTest extends TestCase
{
    private function makeCustomer(): Customer
    {
        return new Customer(
            Uuid::v4(),
            new Name('Ada', 'Lovelace'),
            new Email('ada@example.com'),
            new Address('UA', 'Kyiv', 'Main 1', '01001')
        );
    }

    private function makeOrder(Customer $c): Order
    {
        return new Order(
            Uuid::v4(),
            $c,
            new Address('UA', 'Kyiv', 'Ship 1', '01001'),
            new Money('USD', 0)
        );
    }

    public function testAddItemRecalculatesTotal(): void
    {
        $c = $this->makeCustomer();
        $o = $this->makeOrder($c);

        $o->addItem(new OrderItemDetails(ProductId::new(), 2, new Money('USD', 1500)));
        $o->addItem(new OrderItemDetails(ProductId::new(), 1, new Money('USD', 500)));

        $this->assertSame('USD', $o->totalPrice()->currency);
        $this->assertSame(3500, $o->totalPrice()->amountCents); // 2*1500 + 1*500
    }

    public function testCannotModifyWhenShipped(): void
    {
        $c = $this->makeCustomer();
        $o = $this->makeOrder($c);

        $o->addItem(new OrderItemDetails(ProductId::new(), 1, new Money('USD', 1000)));

        $o->changeStatus(OrderStatus::CONFIRMED);
        $o->changeStatus(OrderStatus::SHIPPED);

        $this->expectException(ValidationException::class);
        $o->addItem(new OrderItemDetails(ProductId::new(), 1, new Money('USD', 500)));
    }

    public function testInvalidStatusTransitionThrows(): void
    {
        $c = $this->makeCustomer();
        $o = $this->makeOrder($c);

        // NEW -> SHIPPED не можна
        $this->expectException(ValidationException::class);
        $o->changeStatus(OrderStatus::SHIPPED);
    }

    public function testCannotMixCurrenciesInOrder(): void
    {
        $c = $this->makeCustomer();
        $o = $this->makeOrder($c);

        $o->addItem(new OrderItemDetails(ProductId::new(), 1, new Money('USD', 1000)));

        $this->expectException(ValidationException::class);
        $o->addItem(new OrderItemDetails(ProductId::new(), 1, new Money('EUR', 1000)));
    }
}
