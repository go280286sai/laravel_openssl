<?php

namespace go280286sai\laravel_openssl\Log;

class LogMessage extends Log
{
    /**
     * @param string $text
     * @return void
     */
    public static function send(string $text): void
    {
        $log = new self();
        $log->write($text);
    }
}
