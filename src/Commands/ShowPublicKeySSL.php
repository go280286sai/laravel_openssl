<?php

namespace go280286sai\laravel_openssl\Commands;

use go280286sai\laravel_openssl\Log\LogMessage;
use go280286sai\laravel_openssl\Models\Ssl_search;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ShowPublicKeySSL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openssl:show_public';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show public key';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            $title = $this->ask('Are you want to see public key? y/n');
            if ($title == 'y' || $title == 'yes') {
                echo Ssl_search::show_public().PHP_EOL;
                echo 'Personal key: '.Ssl_search::$ssl_public_key.PHP_EOL;
                LogMessage::send('Show public of date:' . Carbon::now());
            }
            elseif ($title == 'n' || $title == 'no') {
                LogMessage::send('Not show public of date:' . Carbon::now());
            }
            else {
                LogMessage::send('Arg is empty of date:' . Carbon::now());
                throw new \Exception('Arg is empty');
            }
        } catch (\Exception $e) {
            LogMessage::send($e->getMessage() . ' of date:' . Carbon::now());
            $this->error($e->getMessage());
        }
    }
}
