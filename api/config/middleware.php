<?php

declare(strict_types=1);

use App\Http\Middleware\Catchers\DomainExceptionMiddleware;
use App\Http\Middleware\Catchers\ValidationExceptionMiddleware;
use App\Http\Middleware\ClearEmptyInputMiddleware;
use App\Http\Middleware\UnauthorizedStatusCodeReplaceMiddleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;

return static function (App $app) {
    $app->add(ValidationExceptionMiddleware::class);
    $app->add(DomainExceptionMiddleware::class);
    $app->add(UnauthorizedStatusCodeReplaceMiddleware::class);
    $app->add(ClearEmptyInputMiddleware::class);
    $app->addBodyParsingMiddleware();
    $app->add(ErrorMiddleware::class);
};
