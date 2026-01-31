<?php
declare(strict_types=1);

namespace Tests\Sales\ValueObject;

use App\Sales\ValueObject\Money;
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    public function testAddSameCurrency(): void
    {
        $a = new Money('USD', 5000);
        $b = new Money('USD', 2500);

        $c = $a->add($b);

        $this->assertSame('USD', $c->currency);
        $this->assertSame(7500, $c->amountCents);
    }
}
