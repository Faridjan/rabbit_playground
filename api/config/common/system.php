<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Slim\CallableResolver;
use Slim\Interfaces\CallableResolverInterface;

return [
    CallableResolverInterface::class => static function (ContainerInterface $container): CallableResolver {
        return new CallableResolver($container);
    },
    'config' => [
        'env' => getenv('APP_ENV') ?: 'prod',
        'debug' => (bool)getenv('APP_DEBUG')
    ]
];
