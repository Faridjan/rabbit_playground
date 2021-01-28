<?php

declare(strict_types=1);

namespace App\Http\Action\Post;


use App\Http\Response\JsonResponse;
use App\ReadModel\Post\PostFetcher;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PostAction implements RequestHandlerInterface
{
    private PostFetcher $fetcher;

    public function __construct(PostFetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse($this->fetcher->getAll());
    }
}
