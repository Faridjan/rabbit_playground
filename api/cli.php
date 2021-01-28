<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\ServerRequestFactory;

require_once __DIR__ . '/vendor/autoload.php';

$redis = new Predis\Client(['host' => 'api-redis']);

$container = require __DIR__ . '/config/container.php';
$app = (require __DIR__ . '/config/app.php')($container);
$em = $container->get(EntityManagerInterface::class);
function json(string $method, string $path, array $body = [], array $headers = []): ServerRequestInterface
{
    $request = (new ServerRequestFactory())->createServerRequest($method, $path, ['REMOTE_ADDR' => '99.99.99.99'])
        ->withHeader('Accept', 'application/json')
        ->withHeader('Content-Type', 'application/json');

    foreach ($headers as $name => $value) {
        $request = $request->withHeader($name, $value);
    }
    $request->getBody()->write(json_encode($body, JSON_THROW_ON_ERROR));

    return $request;
}


$startTime = microtime(true);


// ADD Doctrine...
//for ($i = 1; $i <= 10000; $i++) {
//    $app->handle(json('POST', '/add', ['title' => "Post #$i", 'description' => "Description #$i"]));
//    $em->getConnection()->close();
//}

// ADD Redis...
//for ($i = 1; $i <= 10000; $i++) {
//    $data = ['id' => 'cc24f3af-e537-49a5-8adc-a515d13620a4', "Title $i", "Description $i"];
//    $redis->hSet("post:post$i", 'data', serialize($data));
//}

// GET Doctrine...
$response = $app->handle(json('GET', '/'));
$content = json_decode((string)$response->getBody(), true);
print_r(count($content));
echo PHP_EOL;

// GET Redis...
//for ($i = 1; $i <= 10000; $i++) {
//    $redis->hget("post:post$i", 'data');
//}

$endTime = microtime(true);
echo date("H:i:s:m", (int)$endTime - (int)$startTime) . PHP_EOL;
