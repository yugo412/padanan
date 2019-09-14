<?php

namespace App\Console\Commands\Twitter;

use App\Facades\Twitter;
use App\Jobs\Word\ReplyQuestionJob;
use Illuminate\Console\Command;

class AskWordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twitter:word:ask';

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
        $hashtag = '#tanyakata';

        $results = Twitter::search($hashtag, [
            'q' => $hashtag,
            'result_type' => 'mixed',
            'count' => 100,
        ]);

        foreach ($results as $result) {
            dispatch(new ReplyQuestionJob($result));
        }
    }
}
