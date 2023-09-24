<?php

namespace go280286sai\laravel_openssl\Log;

interface Logs
{
    public function write(string $text): void;
}
