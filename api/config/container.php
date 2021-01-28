<?php

declare(strict_types=1);

use Dotenv\Dotenv;

$dotenv = Dotenv::createUnsafeMutable(__DIR__ . '/../');
$dotenv->load();

$builder = new DI\ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/dependencies.php');

return $builder->build();
