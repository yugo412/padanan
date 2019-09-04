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
use Illuminate\Support\Str;

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
            $keyword = str_replace($this->replaces, null, $question->tweet);

            $patterns = [
                '/@\w+/', // remove mentions
                '@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?).*$)@', // remove urls
            ];

            // remove links from retweet
            $keyword = strtolower(trim(preg_replace($patterns, '', $keyword)));

            $terms = Term::where('origin', $keyword)
//                ->orWhere('locale', $keyword)
                ->orderByDesc('total_likes')
                ->take(10)
                ->get();

            if (!empty($terms)) {
                $placeholders = [
                    'bot_emoji' => '🤖',
                    'username' => $this->tweet->user->screen_name,
                    'origin' => $terms->first()->origin,
                    'locale' => $terms->first()->locale,
                    'link' => $link = route('term.search', ['e' => $keyword]),
                    'line' => PHP_EOL,
                    'double_line' => str_repeat(PHP_EOL, 2),
                ];

                if ($terms->count() > 1) {
                    $text = __('@:username :bot_emoji :origin = :locale:linePadanan lainnya: :link:line#padanan', $placeholders);

                    $locales = [];
                    foreach ($terms as $term) {
                        array_push($locales, $term->locale);
                    }

                    $placeholders['locales'] = implode(PHP_EOL, array_unique($locales));

                    $text = __('@:username :bot_emoji :origin::double_line:locales:double_line#padanan', $placeholders);

                    if (strlen($text) >= 280) {
                        $text = Str::limit($text, 200, sprintf('%s%s', str_repeat(PHP_EOL, 2), $link));
                    }
                } else {
                    $text = __('@:username :bot_emoji :origin = :locale #padanan', $placeholders);
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
