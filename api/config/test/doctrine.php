<?php

declare(strict_types=1);

return [
    'config' => [
        'doctrine' => [
            'dev_mode' => true,
            'cache_dir' => null,
            'proxy_dri' => __DIR__ . '/../../var/cache/' . PHP_SAPI . '/doctrine/proxy',
        ]
    ]

];
