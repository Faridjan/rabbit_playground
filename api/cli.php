<?php

declare(strict_types=1);

use App\Console\Amqp\ProduceCommand;
use App\Model\Type\UUIDType;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

require_once __DIR__ . '/vendor/autoload.php';

$container = require_once __DIR__ . '/config/container.php';

$application = new Application();

$command = $application->add(new ProduceCommand($container->get(AMQPStreamConnection::class)));
$commandTester = new CommandTester($command);


$transport = (new Swift_SmtpTransport('mailer', 1025))
    ->setUsername('app')
    ->setPassword('secret')
    ->setEncryption('tcp');

$mailer = new Swift_Mailer($transport);

$message = (new Swift_Message('Test message'))
    ->setFrom('app@auto.kz')
    ->setTo('fred@mail.ru')
    ->setBody('Test message');


for ($i = 1; $i <= 1000; $i++) {
    sleep(rand(1, 3));
    if ($mailer->send($message)) {
        $commandTester->execute(
            [
                'command' => $command->getName(),
                'type' => 'send_mail.success',
                'user_id' => UUIDType::generate()->getValue(),
                'mail' => $message->getBody()
            ]
        );
    } else {
        $commandTester->execute(
            [
                'command' => $command->getName(),
                'type' => 'send_mail.error',
                'user_id' => UUIDType::generate()->getValue(),
                'mail' => $message->getBody()
            ]
        );
    }
}
