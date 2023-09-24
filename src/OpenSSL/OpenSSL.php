<?php

namespace go280286sai\laravel_openssl\OpenSSL;

class OpenSSL
{
    /**
     * @var string
     */
    protected static string $path = __DIR__ . '/files/ssl/';

    public function __construct()
    {
        if (!file_exists(self::$path . 'private.pem') && !file_exists(self::$path . 'public.pem')) {
            self::generation_ssl();
        }
    }

    /**
     * @return void
     * @author Aleksander Storchak <go280286sai@gmail.com>
     */
    public static function generation_ssl(): void
    {
        $privateKey = openssl_pkey_new(config('openssl'));
        openssl_pkey_export($privateKey, $privateKeyPEM);
        $publicKey = openssl_pkey_get_details($privateKey);
        $publicKeyPEM = $publicKey["key"];
        self::save_ssl_keys($privateKeyPEM, $publicKeyPEM);
    }

    /**
     * @param string $privateKeyPEM
     * @param string $publicKeyPEM
     * @return void
     * @author Aleksander Storchak <go280286sai@gmail.com>
     */
    public static function save_ssl_keys(string $privateKeyPEM, string $publicKeyPEM): void
    {
        self::save_private_key($privateKeyPEM);
        self::save_public_key($publicKeyPEM);
    }

    /**
     * @param string $publicKey
     * @return string
     * @author Aleksander Storchak <go280286sai@gmail.com>
     */
    public static function get_public_key(string $publicKey = 'public'): string
    {
        $path = self::$path . $publicKey . '.pem';
        $file = fopen($path, 'r');
        $publicKeyPEM = fread($file, filesize($path));
        fclose($file);

        return $publicKeyPEM;
    }

    /**
     * @param string $publicKey
     * @return void
     * @author Aleksander Storchak <go280286sai@gmail.com>
     */
    public static function remove(string $publicKey): void
    {
        $path = self::$path . $publicKey . '.pem';
        unlink($path);
    }

    /**
     * @param string $publicKeyPEM
     * @param string $publicKey
     * @return void
     * @author Aleksander Storchak <go280286sai@gmail.com>
     */
    public static function save_public_key(string $publicKeyPEM, string $publicKey = 'public'): void
    {
        $file = fopen(self::$path . $publicKey . '.pem', 'w');
        fwrite($file, $publicKeyPEM);
        fclose($file);
    }

    /**
     * @param string $privateKeyPEM
     * @return void
     * @author Aleksander Storchak <go280286sai@gmail.com>
     */
    public static function save_private_key(string $privateKeyPEM): void
    {
        file_put_contents(self::$path . 'private.pem', $privateKeyPEM);
    }

    /**
     * @return string
     * @author Aleksander Storchak <go280286sai@gmail.com>
     */
    protected static function get_private_key(): string
    {
        $file = fopen(self::$path . 'private.pem', 'r');
        $privateKeyPEM = fread($file, filesize(self::$path . 'private.pem'));
        fclose($file);

        return $privateKeyPEM;
    }
}
