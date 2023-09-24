<?php

namespace go280286sai\laravel_openssl\OpenSSL;

use go280286sai\laravel_openssl\OpenSSL\OpenSSL;

class EncryptSSL extends OpenSSL
{
    /**
     * @param string $textToEncrypt
     * @param string $publicKey
     * @return array
     */
    public function encrypt(string $textToEncrypt, string $publicKey): array
    {
        openssl_public_encrypt($textToEncrypt, $encryptedData, $publicKey);
        openssl_sign($textToEncrypt, $signature, static::get_private_key(), OPENSSL_ALGO_SHA256);

        return [base64_encode($encryptedData), base64_encode($signature)];
    }
}
