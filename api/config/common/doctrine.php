<?php

declare(strict_types=1);

use App\Infrastructure\Doctrine\Factory\EntityManagerFactory;
use App\Infrastructure\Doctrine\Type\UUIDTypeDb;
use Doctrine\ORM\EntityManagerInterface;

return [
    EntityManagerInterface::class => Di\factory(EntityManagerFactory::class),
    'config' => [
        'doctrine' => [
            'dev_mode' => false,
            'cache_dir' => __DIR__ . '/../../var/cache/doctrine/cache',
            'proxy_dir' => __DIR__ . '/../../var/cache/doctrine/proxy',
            'connection' => [
                'driver' => 'pdo_pgsql',
                'host' => getenv('DB_HOST'),
                'user' => getenv('DB_USER'),
                'password' => getenv('DB_PASSWORD'),
                'dbname' => getenv('DB_NAME'),
                'charset' => 'utf8'
            ],
            'subscribers' => [],
            'metadata_dirs' => [
                __DIR__ . '/../../src/Model/Post/Entity',
            ],
            'types' => [
                UUIDTypeDb::NAME => UUIDTypeDb::class,
            ]
        ]
    ]
];
