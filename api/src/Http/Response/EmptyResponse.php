<?php

declare(strict_types=1);

namespace App\Http\Response;

use Fig\Http\Message\StatusCodeInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Response;

class EmptyResponse extends Response
{
    /**
     * @param int $status
     */
    public function __construct(int $status = StatusCodeInterface::STATUS_NO_CONTENT)
    {
        parent::__construct(
            $status,
            null,
            (new StreamFactory())->createStreamFromResource(fopen('php://temp', 'rb'))
        );
    }
}
