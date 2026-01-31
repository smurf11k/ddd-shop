<?php
declare(strict_types=1);

namespace App\Sales\ValueObject;

use App\Shared\Exception\ValidationException;

final readonly class Name
{
    public string $first;
    public string $last;

    public function __construct(string $first, string $last)
    {
        $first = trim($first);
        $last = trim($last);

        if ($first === '' || $last === '') {
            throw new ValidationException("Name cannot be empty.");
        }

        $this->first = $first;
        $this->last = $last;
    }

    public function full(): string
    {
        return "{$this->first} {$this->last}";
    }
}
