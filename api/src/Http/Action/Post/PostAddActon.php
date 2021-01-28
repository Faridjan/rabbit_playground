<?php

declare(strict_types=1);

namespace App\Http\Action\Post;


use App\Http\Response\JsonResponse;
use App\Model\Post\Command\Add\Handler;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PostAddActon implements RequestHandlerInterface
{
    private Handler $handler;

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();

        $title = $data['title'] ?? '';
        $description = $data['description'] ?? '';


        $this->handler->handle($title, $description);

        return new JsonResponse([], 200);
    }
}
