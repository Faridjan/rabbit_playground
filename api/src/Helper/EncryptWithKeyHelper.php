<?php

declare(strict_types=1);

namespace App\Helper;

class EncryptWithKeyHelper
{
    protected const CYPHER_METHOD = 'aes-128-cbc';
    private string $iv = '1234567891011121';

    protected string $key;
    protected bool $withoutSymbols;

    public function __construct(string $key, bool $randomCryptResult = true, bool $withoutSymbols = true)
    {
        if ($randomCryptResult) {
            $this->iv = self::randomIv();
        }
        $this->key = $key;
        $this->withoutSymbols = $withoutSymbols;
    }

    /**
     * @param string $message
     * @return string
     */
    public function safeEncrypt(string $message): string
    {
        $key = $this->key;
        $crypted = openssl_encrypt($message, self::CYPHER_METHOD, $key, $options = 0, $this->iv);

        if ($this->withoutSymbols) {
            return self::replaceToCode($crypted);
        }

        return $crypted;
    }

    /**
     * @param string $message
     * @return string
     */
    public function safeDecrypt(string $message): string
    {
        $key = $this->key;

        if ($this->withoutSymbols) {
            $message = self::replaceToSymbols($message);
        }

        return openssl_decrypt($message, self::CYPHER_METHOD, $key, $options = 0, $this->iv);
    }

    private static function randomIv(): string
    {
        return openssl_random_pseudo_bytes(
            openssl_cipher_iv_length(self::CYPHER_METHOD)
        );
    }

    private static function replaceToSymbols(string $message)
    {
        return str_replace(
            ['_55-auto.kz-ff_', '_11-auto.kz-qq_', '_22-auto.kz-yy_', '_77-auto.kz-tt_'],
            ['/', '==', '+', '='],
            $message
        );
    }

    private static function replaceToCode(string $message)
    {
        return str_replace(
            ['/', '==', '+', '='],
            ['_55-auto.kz-ff_', '_11-auto.kz-qq_', '_22-auto.kz-yy_', '_77-auto.kz-tt_'],
            $message
        );
    }
}
