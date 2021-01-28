<?php

use App\Helper\EncryptWithKeyHelper;
use App\Infrastructure\Proxy\HttpClientReplacer;
use App\Infrastructure\Proxy\JWTConverter;
use Proxy\OAuth\Helpers\DotEnvConfigStorage;
use Proxy\OAuth\Interfaces\ConfigStorageInterface;
use Proxy\OAuth\Interfaces\ConverterInterface;
use Proxy\OAuth\Interfaces\HttpClientInterface;
use Psr\Container\ContainerInterface;

return [
    ConverterInterface::class => static function (ContainerInterface $container) {
        $config = $container->get('config')['oauth_proxy'];
        return new JWTConverter(new EncryptWithKeyHelper($config['key'], false));
    },
    ConfigStorageInterface::class => static function () {
        $configsEnv = new DotEnvConfigStorage(__DIR__ . '/../../../');
        $configsEnv->load();
        return $configsEnv;
    },
    HttpClientInterface::class => static function () {
        return new HttpClientReplacer();
    },

    'config' => [
        'oauth_proxy' => [
            'key' => 'auto.kz'
        ]
    ]
];
