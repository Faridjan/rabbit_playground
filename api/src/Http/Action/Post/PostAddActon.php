<?php

declare(strict_types=1);

namespace App\Http\Action\Post;

use App\Http\Response\JsonResponse;
use App\Infrastructure\Amqp\AMQPHelper;
use App\Model\Post\Command\Add\Handler;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PostAddActon implements RequestHandlerInterface
{
    private Handler $handler;

    private AMQPStreamConnection $connection;

    public function __construct(Handler $handler, AMQPStreamConnection $connection)
    {
        $this->handler = $handler;
        $this->connection = $connection;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();

        $title = $data['title'] ?? '';
        $description = $data['description'] ?? '';

        $this->handler->handle($title, $description);

        $connection = $this->connection;
        $channel = $this->connection->channel();

        AMQPHelper::initNotifications($channel);
        AMQPHelper::registerShutdown($connection, $channel);

        $data = [
            'type' => 'notification',
            'message' => 'Post added',
            'post' => [
                'title' => $title
            ]
        ];

        $message = new AMQPMessage(
            json_encode($data),
            ['content_type' => 'text/plain']
        );

        $channel->basic_publish($message, AMQPHelper::EXCHANGE_NOTIFICATIONS);

        return new JsonResponse([], 200);
    }
}
