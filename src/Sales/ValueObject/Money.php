<?php
declare(strict_types=1);

namespace App\Sales\ValueObject;

use App\Shared\Exception\ValidationException;

final readonly class Money
{
    public string $currency;
    public int $amountCents;

    public function __construct(string $currency, int $amountCents)
    {
        $currency = strtoupper(trim($currency));

        if (!preg_match('/^[A-Z]{3}$/', $currency)) {
            throw new ValidationException("Invalid currency: {$currency}");
        }
        if ($amountCents < 0) {
            throw new ValidationException("Money amount cannot be negative.");
        }

        $this->currency = $currency;
        $this->amountCents = $amountCents;
    }

    public function add(self $other): self
    {
        $this->assertSameCurrency($other);
        return new self($this->currency, $this->amountCents + $other->amountCents);
    }

    public function subtract(self $other): self
    {
        $this->assertSameCurrency($other);
        if ($this->amountCents < $other->amountCents) {
            throw new ValidationException("Resulting money cannot be negative.");
        }
        return new self($this->currency, $this->amountCents - $other->amountCents);
    }

    public function equals(self $other): bool
    {
        return $this->currency === $other->currency && $this->amountCents === $other->amountCents;
    }

    public function format(): string
    {
        $amount = number_format($this->amountCents / 100, 2, '.', '');
        $symbol = match ($this->currency) {
            'USD' => '$',
            'EUR' => '€',
            'UAH' => '₴',
            default => $this->currency . ' '
        };
        return $symbol . $amount;
    }

    private function assertSameCurrency(self $other): void
    {
        if ($this->currency !== $other->currency) {
            throw new ValidationException("Currency mismatch: {$this->currency} vs {$other->currency}");
        }
    }
}
