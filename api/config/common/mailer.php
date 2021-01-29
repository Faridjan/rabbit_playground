<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;

return [
    Swift_Mailer::class => function (ContainerInterface $container) {
        $config = $container->get('config')['mailer'];
        $transport = (new Swift_SmtpTransport($config['host'], $config['port']))
            ->setUsername($config['username'])
            ->setPassword($config['password'])
            ->setEncryption($config['encryption']);
        return new Swift_Mailer($transport);
    },
    'config' => [
        'mailer' => [
            'host' => 'mailer',
            'port' => 1025,
            'username' => 'app',
            'password' => 'secret',
            'encryption' => 'tcp',
            'from' => 'api@auto.kz',
        ],
    ],
];