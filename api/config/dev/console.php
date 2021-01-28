<?php

declare(strict_types=1);

use App\Console\FixturesLoadCommand;
use Doctrine\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand;
use Psr\Container\ContainerInterface;

return [
    FixturesLoadCommand::class => static function (ContainerInterface $container) {
        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var array{string} $fixtures
         */
        $fixtures = $container->get('config')['console']['fixtures_paths'];

        return new FixturesLoadCommand(
            $container->get(EntityManagerInterface::class),
            $fixtures
        );
    },
    'config' => [
        'console' => [
            'commands' => [

                CreateCommand::class,
                FixturesLoadCommand::class,

                DropCommand::class,

                DiffCommand::class,
                GenerateCommand::class,
            ],
            'fixtures_paths' => [
                __DIR__ . '/../../tests/Functional/Api'
            ]
        ]
    ]
];
