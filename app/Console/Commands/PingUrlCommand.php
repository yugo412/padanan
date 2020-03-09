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
    protected $signature = 'ping:url {url?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ping current hosted URL on production server';

    private $http;

    /**
     * Create a new command instance.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct();

        $this->http = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = $this->argument('url') ?? config('app.url');

        try {
            $this->http->get($url, [
                'timeout' => 5,
            ]);

            $this->line(__('Ping is OK.'));
            return 0;
        } catch (RequestException $exception) {
            Log::error($exception);
            Mail::to(config('mail.developer'))
                ->send(new AppDownMail($url, $exception->getMessage()));

            $this->line(__('Ping is not OK on :url.', ['url' => $url]));
            return 1;
        }
    }
}
