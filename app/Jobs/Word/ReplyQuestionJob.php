<?php

namespace App\Jobs\Word;

use App\Facades\Dictionary;
use App\Facades\Twitter;
use DG\Twitter\Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SebastianBergmann\CodeCoverage\Report\PHP;

class ReplyQuestionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $tweet;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tweet)
    {
        $this->tweet = $tweet;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $keyword = str_replace('#tanyakata', null, $this->tweet->text);

        $patterns = [
            '/@\w+/', // remove mentions
            '@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?).*$)@', // remove urls
        ];

        $word = Dictionary::explain(trim(preg_replace($patterns, null, $keyword)));

        if (!empty($word['descriptions'])) {
            $formattedDescription = array_map(function ($description) {
                $type = sprintf('(%s)', implode(', ', $description['types']));

                return $type . ' ' . $description['description'];
            }, $word['descriptions']);

            $mentions = array_map(function ($mention) {
                return '@' . $mention->screen_name;
            }, $this->tweet->entities->user_mentions);

            // add original asker
            array_unshift($mentions, '@' . $this->tweet->user->screen_name);

            $placeholders = [
                'line' => PHP_EOL,
                'mentions' => implode(' ', $mentions),
                'heading' => $word['heading'],
                'word' => $word['word'],
                'descriptions' => implode(PHP_EOL, $formattedDescription),
            ];

            $text = __(':mentions :word:line:heading:line:descriptions', $placeholders);

            if (strlen($text) >= 280) {
                $text = Str::limit($text, 200) . str_repeat(PHP_EOL, 2) . 'https://kbbi.kemdikbud.go.id/entri/' . $word['word'];
            }

            try {
                Twitter::reply($text, [
                    'tweet_id' => $this->tweet->id,
                ]);
            } catch (Exception $e) {
                Log::warning($e->getMessage());
            }
        }
    }
}
