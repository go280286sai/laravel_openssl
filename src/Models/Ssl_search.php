<?php

namespace go280286sai\laravel_openssl\Models;

use go280286sai\laravel_openssl\OpenSSL\DecryptSSL;
use go280286sai\laravel_openssl\OpenSSL\EncryptSSL;
use go280286sai\laravel_openssl\OpenSSL\OpenSSL;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ssl_search extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    public static string $ssl_public_key = 'qwerty1234';
    /**
     * @var string[]
     */
    protected $fillable = ['name', 'url'];
    /**
     * @var string
     */
    protected $table = 'ssl_searches';

    /**
     * @param array $data
     * @return int
     */
    public static function add(array $data): int
    {
        $obj = new self();
        $obj->fill($data);
        $obj->save();
        return $obj->id;
    }

    /**
     * @param array $data
     * @param int $id
     * @return void
     */
    public static function set_update(array $data, int $id): void
    {
        $obj = self::find($id);
        $obj->fill($data);
        $obj->save();
    }

    /**
     * @param int $id
     * @return void
     */
    public static function remove(int $id): void
    {
        self::find($id)->delete();
        OpenSSL::remove($id . '_id_public');
    }

    /**
     * @return void
     */
    public static function generation(): void
    {
        OpenSSL::generation_ssl();
    }

    /**
     * @param string $text
     * @param string $publicKey
     * @return array
     */
    public static function encrypt(string $text, string $publicKey): array
    {
        $obj = new EncryptSSL();

        return $obj->encrypt($text, $publicKey);
    }

    /**
     * @param array $text
     * @param string $publicKey
     * @return string
     */
    public static function decrypt(array $text, string $publicKey): string
    {
        $obj = new DecryptSSL();

        return $obj->decrypt($text, $publicKey);
    }

    /**
     * @return string
     */
    public static function show_public(): string
    {
        return OpenSSL::get_public_key();
    }

    /**
     * @param string $publicKey
     * @param int $id
     * @return void
     */
    public static function save_public_key(string $publicKey, int $id): void
    {
        OpenSSL::save_public_key($publicKey, $id . '_id_public');
    }

    /**
     * @param string $publicKey
     * @return string
     */
    public static function get_public_key(string $publicKey): string
    {
        return OpenSSL::get_public_key($publicKey);
    }

    /**
     * @param array $data
     * @return void
     */
    public static function add_resource(array $data): void
    {
        self::save_public_key($data['key'], self::add($data));
    }

    /**
     * @param array $data
     * @param int $id
     * @return void
     */
    public static function update_resource(array $data, int $id): void
    {
        self::set_update($data, $id);
        self::save_public_key($data['key'], $id);
    }
}
