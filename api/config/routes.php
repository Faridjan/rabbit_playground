<?php

declare(strict_types=1);

use App\Http\Action\Post\PostAction;
use App\Http\Action\Post\PostAddActon;
use Slim\App;

return static function (App $app) {
    $app->get('/', PostAction::class);
    $app->post('/add', PostAddActon::class);
};
