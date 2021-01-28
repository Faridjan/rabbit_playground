<?php

declare(strict_types=1);

namespace App\Model\Type;

use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class UUIDType
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value, 'The value cannot be empty.');
        Assert::uuid($value, 'The value must be UUID type.');

        $this->value = mb_strtolower($value);
    }

    public function isEqualTo(self $another): bool
    {
        if ($this->getValue() === $another->getValue()) {
            return true;
        }

        return false;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
}
