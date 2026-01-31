<?php
declare(strict_types=1);

namespace App\Sales\ValueObject;

use App\Shared\ValueObject\Uuid;

final readonly class ProductId
{
    public function __construct(public Uuid $uuid) {}

    public static function new(): self
    {
        return new self(Uuid::v4());
    }

    public function equals(self $other): bool
    {
        return $this->uuid->equals($other->uuid);
    }

    public function toString(): string
    {
        return $this->uuid->value;
    }
}
