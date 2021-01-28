<?php

declare(strict_types=1);

namespace App\Helper;

class CamelToSnakeCaseHelper
{
    public function __invoke(string $input): string
    {
        return $this->transform($input);
    }

    public static function transform(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}
