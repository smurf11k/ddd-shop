<?php
declare(strict_types=1);

namespace App\Sales\ValueObject;

use App\Shared\Exception\ValidationException;

final readonly class Dimensions
{
    public function __construct(
        public float $length,
        public float $width,
        public float $height
    ) {
        foreach (['length' => $length, 'width' => $width, 'height' => $height] as $k => $v) {
            if ($v <= 0) {
                throw new ValidationException("Dimension {$k} must be > 0.");
            }
        }
    }

    public function volume(): float
    {
        return $this->length * $this->width * $this->height;
    }

    public function fitsMax(float $maxL, float $maxW, float $maxH): bool
    {
        return $this->length <= $maxL && $this->width <= $maxW && $this->height <= $maxH;
    }
}
