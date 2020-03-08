<?php

namespace App\Console\Commands;

use App\Mail\AppDownMail;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PingUrlCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ping:url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ping current hosted URL on production server';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param Client $client
     * @return mixed
     */
    public function handle(Client $client)
    {
        try {
            $client->get(config('app.url'));
        } catch (RequestException $exception) {
            Log::error($exception);
            Mail::to(config('mail.developer'))
                ->send(new AppDownMail($exception->getMessage()));
        }
    }
}
