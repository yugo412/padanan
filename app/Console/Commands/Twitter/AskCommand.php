<?php

namespace App\Console\Commands\Twitter;

use App\Facades\Twitter;
use App\Jobs\Twitter\ReplyQuestionJob;
use App\Models\TwitterAsk;
use Illuminate\Console\Command;

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
        $hashtag = '#padanan';
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
            $mentions = array_map(function ($user) {
                return $user->screen_name;
            }, $tweet->entities->user_mentions);

            if (in_array($username, $mentions)) {
                dispatch(new ReplyQuestionJob($tweet, [
                    $hashtag,
                    '@' . $username,
                ]));
            }
        }
    }
}
