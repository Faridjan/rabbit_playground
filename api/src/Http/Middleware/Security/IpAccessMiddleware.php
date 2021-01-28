<?php

declare(strict_types=1);

namespace App\Http\Middleware\Security;

use App\Http\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class IpAccessMiddleware implements MiddlewareInterface
{
    private array $trusted;

    public function __construct(array $trusted)
    {
        $this->trusted = $trusted;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var string $ipAddress */
        $ipAddress = $request->getAttribute('ip_address');
        if (in_array($ipAddress, $this->trusted)) {
            return $handler->handle($request);
        }

        return new JsonResponse(
            [
                'message' => 'Ip address not allowed.',
            ],
            400
        );
    }
}
