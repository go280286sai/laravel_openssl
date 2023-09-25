<?php

namespace go280286sai\laravel_openssl\OpenSSL;

use go280286sai\laravel_openssl\Log\LogMessage;
use go280286sai\laravel_openssl\OpenSSL\OpenSSL;
use Illuminate\Support\Carbon;

class DecryptSSL extends OpenSSL
{
    /**
     * @param string $encryptedData
     * @param string $publicKey
     * @return string
     */
    public function decrypt(string $encryptedData, string $publicKey): string
    {
        try {
            $textToDecrypt = base64_decode($encryptedData);
            openssl_private_decrypt($textToDecrypt, $decryptedData, parent::get_private_key());

            return $decryptedData;
        } catch (\Exception $e) {
            LogMessage::send($e->getMessage() . ' of date:' . Carbon::now());

            return $e->getMessage();
        }
    }
}
