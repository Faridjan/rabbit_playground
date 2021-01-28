<?php

namespace App\Infrastructure\Proxy;

use App\Helper\EncryptWithKeyHelper;
use Proxy\OAuth\Interfaces\ConverterInterface;

class JWTConverter implements ConverterInterface
{
    private EncryptWithKeyHelper $crypter;

    public function __construct(EncryptWithKeyHelper $crypter)
    {
        $this->crypter = $crypter;
    }

    public function fromFrontendToJWT(string $oauthData): array
    {
        $authArr = json_decode($oauthData, true);

        foreach ($authArr as $key => $value) {
            $authArr[$key] = $this->crypter->safeDecrypt($value);
        }

        return $authArr;
    }

    public function fromJWTToFrontend(array $jwt): string
    {
        foreach ($jwt as $key => $value) {
            $jwt[$key] = $this->crypter->safeEncrypt($value);
        }

        return json_encode($jwt, JSON_FORCE_OBJECT);
    }
}
