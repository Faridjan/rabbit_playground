<?php

namespace App\Infrastructure\Proxy;

use Exception;
use Proxy\OAuth\Interfaces\HttpClientInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Response;

class HttpClientReplacer implements HttpClientInterface
{
    private ?App $app = null;

    public function get(string $url, array $body = [], array $headers = [], array $options = []): ResponseInterface
    {
        return $this->process('GET', $url, $body, $headers, $options);
    }

    public function post(string $url, array $body = [], array $headers = [], array $options = []): ResponseInterface
    {
        return $this->process('POST', $url, $body, $headers, $options);
    }

    public function process(
        string $method,
        string $url,
        array $body = [],
        array $headers = [],
        array $options = []
    ): ResponseInterface {
        $response = $this->app()->handle(self::json($method, $url, $body, $headers));
        if ($response->getStatusCode() !== 200) {
            if (isset($options['http_errors']) && $options['http_errors'] === false) {
                return new Response(400);
            }
            $responseBody = json_decode((string)$response->getBody(), true);

            $responseMessage = isset($responseBody['message']) ? $responseBody['message'] : '';
            $responseCode = $response->getStatusCode();

            throw new Exception($responseMessage, $responseCode);
        }
        return $response;
    }

    private function app(): App
    {
        /** @var ContainerInterface */
        $container = require __DIR__ . '/../../../../config/container.php';

        if ($this->app === null) {
            /** @var App */
            $this->app = (require __DIR__ . '/../../../../config/app.php')($container);
        }
        return $this->app;
    }

    public static function json(
        string $method,
        string $path,
        array $body = [],
        array $headers = []
    ): ServerRequestInterface {
        $request = (new ServerRequestFactory())->createServerRequest($method, $path, ['REMOTE_ADDR' => '99.99.99.99'])
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');

        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }
        $request->getBody()->write(json_encode($body, JSON_THROW_ON_ERROR));
        return $request;
    }
}
