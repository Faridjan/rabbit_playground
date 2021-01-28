<?php

declare(strict_types=1);

use App\Infrastructure\Symfony\Factory\Validator\ValidatorFactory;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use function DI\factory;

return [
    ValidatorInterface::class => factory(ValidatorFactory::class)
];
