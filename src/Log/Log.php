<?php

namespace go280286sai\laravel_openssl\Log;

class Log implements Logs
{
    /**
     * Path to file
     * @var string
     */
    protected string $path;

    /**
     * @param string $path
     */
    public function __construct(string $path = 'default')
    {
        $this->path = __DIR__.'/files/logs/'.$path.'.txt';
    }

    /**
     * @param string $text
     * @return void
     * @author Aleksander Storchak <go280286sai@gmail.com>
     */
    public function write(string $text): void
    {
        $file = fopen($this->path, 'a+');
        fwrite($file, $text."\n");
        fclose($file);
    }
}
