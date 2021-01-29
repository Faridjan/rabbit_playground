<?php

declare(strict_types=1);

namespace App\Console\Amqp;

use App\Infrastructure\Amqp\AMQPHelper;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProduceCommand extends Command
{
    private $connection;

    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('amqp:demo:produce')
            ->addArgument('user_id', InputArgument::REQUIRED)
            ->addArgument('mail', InputArgument::REQUIRED)
            ->addArgument('type', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<comment>Produce message</comment>');

        $connection = $this->connection;

        $channel = $this->connection->channel();

        AMQPHelper::initNotifications($channel);
        AMQPHelper::registerShutdown($connection, $channel);

        $data = [
            'type' => $input->getArgument('type'),
            'user_id' => $input->getArgument('user_id'),
            'mail' => $input->getArgument('mail'),
        ];

        $message = new AMQPMessage(
            json_encode($data),
            ['content_type' => 'text/plain']
        );

        $channel->basic_publish($message, AMQPHelper::EXCHANGE_NOTIFICATIONS);

        $output->writeln('<info>Done!</info>');

        return Command::SUCCESS;
    }
}
