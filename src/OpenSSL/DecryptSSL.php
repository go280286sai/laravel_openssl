<?php

namespace go280286sai\laravel_openssl\OpenSSL;

use go280286sai\laravel_openssl\Log\LogMessage;
use go280286sai\laravel_openssl\OpenSSL\OpenSSL;
use Illuminate\Support\Carbon;

class DecryptSSL extends OpenSSL
{
    /**
     * @param array $encryptedData
     * @param string $publicKey
     * @return string|null
     */
    public function decrypt(array $encryptedData, string $publicKey): string|null
    {
        $textToDecrypt = base64_decode($encryptedData[0]);
        $signatureToVerify = base64_decode($encryptedData[1]);
        openssl_private_decrypt($textToDecrypt, $decryptedData, static::get_private_key());
        $verificationResult = openssl_verify($decryptedData, $signatureToVerify, $publicKey, "sha256WithRSAEncryption");
        try {
            if ($verificationResult === 1) {
                return $decryptedData;
            } else {
                throw new \Exception('Signature verification error');
            }
        } catch (\Exception $e) {
            LogMessage::send($e->getMessage() . ' of date:' . Carbon::now());

            return null;
        }
    }
}
