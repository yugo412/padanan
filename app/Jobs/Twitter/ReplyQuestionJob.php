<?php

namespace App\Jobs\Twitter;

use App\Facades\Twitter;
use App\Models\Term;
use App\Models\TwitterAsk;
use DG\Twitter\Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class ReplyQuestionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var object
     */
    private $tweet;

    /**
     * @var array
     */
    private $replaces = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tweet, array $replaces = [])
    {
        $this->tweet = $tweet;
        $this->replaces = $replaces;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $question = TwitterAsk::firstOrCreate([
            'tweet_id' => $this->tweet->id_str,
            'tweet' => $this->tweet->text,
            'user' => $this->tweet->user->screen_name,
        ]);

        if ($question->wasRecentlyCreated) {
            $origin = str_replace($this->replaces, null, $question->tweet);

            $patterns = [
                '/@\w+/', // remove mentions
                '@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?).*$)@', // remove urls
            ];

            // remove links from retweet
            $origin = trim(preg_replace($patterns, '', $origin));

            $term = Term::whereOrigin(trim($origin))->first();

            // get total term by keyword
            $count = Term::whereOrigin(trim($origin))->count();

            if (!empty($term)) {
                $placeholders = [
                    'username' => $this->tweet->user->screen_name,
                    'origin' => $term->origin,
                    'locale' => $term->locale,
                    'link' => route('term.search', ['katakunci' => $origin]),
                    'line' => str_repeat(PHP_EOL, 2),
                ];

                if ($count > 1) {
                    $text = __('@:username :origin = :locale:linePadanan lainnya: :link:line#padanan', $placeholders);
                } else {
                    $text = __('@:username :origin = :locale #padanan', $placeholders);
                }

                try {
                    $reply = Twitter::reply($text, ['tweet_id' => $this->tweet->id]);

                    $question->is_replied = true;
                    $question->save();
                } catch (Exception $e) {
                    Log::warning($e->getMessage(), [
                        'username' => $this->tweet->user->screen_name,
                        'tweet' => $this->tweet->text,
                        'reply' => $text,
                    ]);
                }
            }
        }
    }
}
