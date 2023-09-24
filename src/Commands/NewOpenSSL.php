<?php

namespace go280286sai\laravel_openssl\Commands;

use go280286sai\laravel_openssl\Log\LogMessage;
use go280286sai\laravel_openssl\Models\Ssl_search;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class NewOpenSSL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openssl:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new keys';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle(): void
    {
        try {
            $title = $this->ask('Are you want to generate new keys? y/n');
            if ($title == 'y' || $title == 'yes') {
                Ssl_search::generation();
                LogMessage::send('Generate new keys of date:' . Carbon::now());
            } else {
                LogMessage::send('Arg is empty of date:' . Carbon::now());
                throw new \Exception('Arg is empty');
            }
        } catch (\Exception $e) {
            LogMessage::send($e->getMessage() . ' of date:' . Carbon::now());
            $this->error($e->getMessage());
        }
    }
}
