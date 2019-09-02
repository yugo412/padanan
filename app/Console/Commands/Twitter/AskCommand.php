<?php

namespace App\Console\Commands\Twitter;

use App\Facades\Twitter;
use App\Jobs\Twitter\ReplyQuestionJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twitter:ask';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        Log::debug('Running padanan\'s bot asker.', [
            'at' => now()->format('d-m-Y H:i:s'),
        ]);

        $hashtag = '#tanyapadanan';
        $username = str_replace('@', null, config('twitter.username'));

        $results = Twitter::search($hashtag, [
            'q' => $hashtag,
            'result_type' => 'mixed',
            'count' => 100,
        ]);

        foreach ($results as $tweet) {
            if ($tweet->user->screen_name == $username) {
                continue;
            }

            dispatch(new ReplyQuestionJob($tweet, [
                $hashtag,
                '@' . $username,
            ]));
        }
    }
}
