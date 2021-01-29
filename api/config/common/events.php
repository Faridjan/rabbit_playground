<?php

declare(strict_types=1);

use App\Http\Action\Post\PostAddActon;
use App\Infrastructure\Model\EventDispatcher\Listener\Post\CreatedListener;
use App\Infrastructure\Model\EventDispatcher\SyncEventDispatcher;
use Psr\Container\ContainerInterface;

return [
    SyncEventDispatcher::class => function (ContainerInterface $container) {
        return new SyncEventDispatcher(
            $container,
            [
                PostAddActon::class => [
                    CreatedListener::class,
                    $container->get('config')['mailer']['from']
                ]
            ]
        );
    }
];