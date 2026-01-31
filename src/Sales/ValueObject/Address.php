<?php
declare(strict_types=1);

namespace App\Sales\ValueObject;

use App\Shared\Exception\ValidationException;

final readonly class Address
{
    public string $country;
    public string $city;
    public string $street;
    public string $postalCode;

    public function __construct(string $country, string $city, string $street, string $postalCode)
    {
        $country = trim($country);
        $city = trim($city);
        $street = trim($street);
        $postalCode = trim($postalCode);

        foreach (['country' => $country, 'city' => $city, 'street' => $street] as $k => $v) {
            if ($v === '') {
                throw new ValidationException("Address {$k} cannot be empty.");
            }
        }

        if (!$this->isPostalValid($country, $postalCode)) {
            throw new ValidationException("Invalid postal code for {$country}: {$postalCode}");
        }

        $this->country = $country;
        $this->city = $city;
        $this->street = $street;
        $this->postalCode = $postalCode;
    }

    public function toText(): string
    {
        return "{$this->street}, {$this->city}, {$this->postalCode}, {$this->country}";
    }

    private function isPostalValid(string $country, string $postal): bool
    {
        $cc = strtoupper($country);

        return match ($cc) {
            'UA', 'UKRAINE' => (bool) preg_match('/^\d{5}$/', $postal),
            'US', 'USA' => (bool) preg_match('/^\d{5}(-\d{4})?$/', $postal),
            'PL', 'POLAND' => (bool) preg_match('/^\d{2}-\d{3}$/', $postal),
            default => strlen($postal) >= 3 && strlen($postal) <= 12
        };
    }
}
