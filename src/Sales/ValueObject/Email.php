<?php
declare(strict_types=1);

namespace App\Sales\ValueObject;

use App\Shared\Exception\ValidationException;

final readonly class Email
{
    public string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException("Invalid email: {$value}");
        }

        $this->value = $value;
    }
}
