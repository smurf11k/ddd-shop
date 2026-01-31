<?php
declare(strict_types=1);

namespace Tests\Sales\ValueObject;

use App\Sales\ValueObject\Address;
use App\Shared\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

final class AddressTest extends TestCase
{
    public function testValidUaPostalCode(): void
    {
        $a = new Address('UA', 'Kyiv', 'Khreshchatyk 1', '01001');
        $this->assertSame('Khreshchatyk 1, Kyiv, 01001, UA', $a->toText());
    }

    public function testInvalidUaPostalCodeThrows(): void
    {
        $this->expectException(ValidationException::class);
        new Address('UA', 'Kyiv', 'Khreshchatyk 1', '0100'); // 4 digits
    }

    public function testEmptyCityThrows(): void
    {
        $this->expectException(ValidationException::class);
        new Address('UA', '', 'Street 1', '01001');
    }
}
