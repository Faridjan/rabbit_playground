<?php

declare(strict_types=1);

namespace App\Infrastructure\Amqp;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class AMQPHelper
{
    public const EXCHANGE_NOTIFICATIONS = 'notifications';
    public const QUEUE_NOTIFICATIONS = 'notifications';
    public const QUEUE_MAIL_NOTIFICATIONS = 'mail_send';

    public static function initNotifications(AMQPChannel $channel): void
    {
        $channel->exchange_declare(self::EXCHANGE_NOTIFICATIONS, 'fanout', false, false, true);
        $channel->queue_declare(self::QUEUE_NOTIFICATIONS, false, false, false, true);
        $channel->queue_declare(self::QUEUE_MAIL_NOTIFICATIONS, false, false, false, true);

        $channel->queue_bind(self::QUEUE_NOTIFICATIONS, self::EXCHANGE_NOTIFICATIONS);
        $channel->queue_bind(self::QUEUE_MAIL_NOTIFICATIONS, self::EXCHANGE_NOTIFICATIONS);
    }

    public static function registerShutdown(AMQPStreamConnection $connection, AMQPChannel $channel): void
    {
        register_shutdown_function(
            function (AMQPChannel $channel, AMQPStreamConnection $connection) {
                $channel->close();
                $connection->close();
            },
            $channel,
            $connection
        );
    }
}
