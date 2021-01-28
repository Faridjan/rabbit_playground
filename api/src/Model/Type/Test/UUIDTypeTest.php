<?php

declare(strict_types=1);

namespace App\Model\Type\Test;

use App\Model\Type\UUIDType;
use InvalidArgumentException;
use Monolog\Test\TestCase;
use Ramsey\Uuid\Uuid;

class UUIDTypeTest extends TestCase
{
    public function testSuccess(): void
    {
        $uuid = Uuid::uuid4();
        $uuidType = new UUIDType($value = $uuid->toString());

        self::assertEquals($value, $uuidType->getValue());
    }

    public function testIncorrectValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new UUIDType('string');
    }

    public function testIncorrectValueMessage(): void
    {
        $this->expectExceptionMessage('The value must be UUID type.');
        new UUIDType('string');
    }

    public function testIncorrectNullMessage(): void
    {
        $this->expectExceptionMessage('The value cannot be empty.');
        new UUIDType("0");
    }

    public function testEqualTo(): void
    {
        $uuid = Uuid::uuid4();
        $uuidAnother = Uuid::uuid4();

        $uuidType = new UUIDType($uuid->toString());
        $uuidTypeAnother = new UUIDType($uuid->toString());
        $uuidTypeAnotherYet = new UUIDType($uuidAnother->toString());

        self::assertTrue($uuidType->isEqualTo($uuidTypeAnother));
        self::assertFalse($uuidType->isEqualTo($uuidTypeAnotherYet));
    }

    public function testCase(): void
    {
        $value = Uuid::uuid4()->toString();

        $id = new UUIDType(mb_strtoupper($value));

        self::assertEquals($value, $id->getValue());
    }
}
