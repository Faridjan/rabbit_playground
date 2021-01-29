<?php

declare(strict_types=1);

use App\Console\Amqp\ConsumeCommand;
use App\Console\Amqp\ConsumeMailCommand;
use App\Console\Amqp\ProduceCommand;
use Doctrine\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\Migrations\Tools\Console\Command\LatestCommand;
use Doctrine\Migrations\Tools\Console\Command\ListCommand;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\Migrations\Tools\Console\Command\UpToDateCommand;
use Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Container\ContainerInterface;

return [
    ProduceCommand::class => function (ContainerInterface $container) {
        return new ProduceCommand(
            $container->get(AMQPStreamConnection::class)
        );
    },
    ConsumeCommand::class => function (ContainerInterface $container) {
        return new ConsumeCommand(
            $container->get(AMQPStreamConnection::class)
        );
    },

    ConsumeMailCommand::class => function (ContainerInterface $container) {
        return new ConsumeMailCommand(
            $container->get(AMQPStreamConnection::class),
            $container->get(Swift_Mailer::class)
        );
    },

    'config' => [
        'console' => [
            'commands' => [
                ValidateSchemaCommand::class,

                ProduceCommand::class,
                ConsumeCommand::class,
                ConsumeMailCommand::class,

                ExecuteCommand::class,
                MigrateCommand::class,
                LatestCommand::class,
                ListCommand::class,
                StatusCommand::class,
                UpToDateCommand::class,
            ]
        ]
    ]
];
