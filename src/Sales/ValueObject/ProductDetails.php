<?php
declare(strict_types=1);

namespace App\Sales\ValueObject;

use App\Shared\Exception\ValidationException;

final readonly class ProductDetails
{
    public string $name;
    public string $description;
    public Dimensions $dimensions;

    public function __construct(string $name, string $description, Dimensions $dimensions)
    {
        $name = trim($name);
        if ($name === '') {
            throw new ValidationException("Product name cannot be empty.");
        }

        $this->name = $name;
        $this->description = trim($description);
        $this->dimensions = $dimensions;
    }

    public function shortDescription(int $maxLen = 60): string
    {
        $d = trim($this->description);
        if ($d === '') return $this->name;
        return mb_strlen($d) <= $maxLen ? $d : mb_substr($d, 0, $maxLen - 1) . '…';
    }
}
