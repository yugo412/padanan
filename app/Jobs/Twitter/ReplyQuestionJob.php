<?php

namespace App\Jobs\Twitter;

use App\Facades\Twitter;
use App\Models\Term;
use App\Models\TwitterAsk;
use DG\Twitter\Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
        $keyword = str_replace($this->replaces, null, $this->tweet->text);

        $patterns = [
            '/@\w+/', // remove mentions
            '@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?).*$)@', // remove urls
        ];

        // remove links from retweet
        $keyword = strtolower(trim(preg_replace($patterns, '', $keyword)));

        $question = TwitterAsk::firstOrCreate([
            'tweet_id' => $this->tweet->id_str,
            'tweet' => $this->tweet->text,
            'user' => $this->tweet->user->screen_name,
            'keyword' => $keyword,
        ]);

        if ($question->wasRecentlyCreated === true) {
            $terms = Term::where('origin', $keyword)
                ->orderByDesc('total_likes')
                ->take(10)
                ->get();

            $mentions = array_map(function ($mention) {
                return '@' . $mention->screen_name;
            }, $this->tweet->entities->user_mentions);

            // add original asker
            array_unshift($mentions, '@' . $this->tweet->user->screen_name);

            if ($terms->count() >= 1) {
                $placeholders = [
                    'bot_emoji' => '🤖',
                    'mentions' => implode(' ', $mentions),
                    'username' => $this->tweet->user->screen_name,
                    'origin' => $terms->first()->origin,
                    'locale' => $terms->first()->locale,
                    'link' => $link = route('term.search', ['e' => $keyword]),
                    'line' => PHP_EOL,
                    'double_line' => str_repeat(PHP_EOL, 2),
                ];

                if ($terms->count() > 1) {
                    $locales = [];
                    foreach ($terms as $term) {
                        array_push($locales, $term->locale);
                    }

                    $placeholders['locales'] = implode(PHP_EOL, array_unique($locales));

                    $text = __(':mentions :bot_emoji :origin::double_line:locales:double_line#padanan', $placeholders);

                    if (strlen($text) >= 280) {
                        $text = Str::limit($text, 200, sprintf('%s%s', str_repeat(PHP_EOL, 2), $link));
                    }
                } else {
                    $text = __(':mentions :bot_emoji :origin = :locale #padanan', $placeholders);
                }

                try {
                    Twitter::reply($text, ['tweet_id' => $this->tweet->id]);

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
