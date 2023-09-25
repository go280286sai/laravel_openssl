<?php

namespace go280286sai\laravel_openssl\OpenSSL;

use go280286sai\laravel_openssl\OpenSSL\OpenSSL;

class EncryptSSL extends OpenSSL
{
    /**
     * @param string $textToEncrypt
     * @param string $publicKey
     * @return string
     */
    public function encrypt(string $textToEncrypt, string $publicKey): string
    {
        openssl_public_encrypt($textToEncrypt, $encryptedData, $publicKey);

        return base64_encode($encryptedData);
    }
}
