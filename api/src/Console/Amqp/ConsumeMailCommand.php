<?php

declare(strict_types=1);

namespace App\Console\Amqp;

use App\Infrastructure\Amqp\AMQPHelper;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumeMailCommand extends Command
{
    private $connection;
    /**
     * @var Swift_Mailer
     */
    private Swift_Mailer $mailHandler;

    public function __construct(AMQPStreamConnection $connection, Swift_Mailer $mailHandler)
    {
        $this->connection = $connection;
        parent::__construct();
        $this->mailHandler = $mailHandler;
    }

    protected function configure(): void
    {
        $this->setName('amqp:demo:consume-mail');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<comment>Mail Consume messages</comment>');

        $connection = $this->connection;

        $channel = $connection->channel();


        AMQPHelper::initNotifications($channel);
        AMQPHelper::registerShutdown($connection, $channel);

        $consumerTag = 'consumer_' . getmypid();
        $channel->basic_consume(
            AMQPHelper::QUEUE_MAIL_NOTIFICATIONS,
            $consumerTag,
            false,
            false,
            false,
            false,
            function ($message) use ($output) {
                $output->writeln(print_r(json_decode($message->body, true)['message'], true));

                if (json_decode($message->body, true)['post']['title'] == "fred") {
                    sleep(10);
                }

                $mailMessage = (new Swift_Message('New Post'))
                    ->setTo('admin@auto.kz')
                    ->setFrom('api@auto.kz')
                    ->setBody('Post added to Api');

                $this->mailHandler->send($mailMessage);

                /** @var AMQPChannel $channel */
                $channel = $message->delivery_info['channel'];
                $channel->basic_ack($message->delivery_info['delivery_tag']);
            }
        );

        while (\count($channel->callbacks)) {
            $channel->wait();
        }

        $output->writeln('<info>Done!</info>');

        return Command::SUCCESS;
    }
}
