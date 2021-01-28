<?php

declare(strict_types=1);

namespace App\Model\Type;

use Webmozart\Assert\Assert;

class NameType
{
    private string $value;

    /**
     * EmailType constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value, 'The name cannot be empty.');
        Assert::minLength($value, 3, 'The name value should have 3 characters or more.');
        $this->value = mb_strtoupper($value);
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
}
