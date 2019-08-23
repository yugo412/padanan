<?php

namespace App\Listeners\Term;

use App\Facades\Sastrawi;
use App\Models\Definition;
use App\Models\Word;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class TermDefinition
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://kateglo.com/',
            'http_errors' => false,
            'timeout' => 5,
        ]);
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $term = $event->term;

        $words = explode(' ', Sastrawi::stem($term->locale));

        foreach ($words as $word) {
            // TODO: move to queue job
            $this->getPhrase($word);
        }
    }

    /**
     * @param string $word
     */
    private function getPhrase(string $word): void
    {
        if (Word::wherePhrase($word)->count() >= 1) {
            return;
        }

        $response = $this->client->get('api.php', [
            'query' => [
                'format' => 'json',
                'phrase' => $word,
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $body = (string)$response->getBody();

            if (!Str::contains($body, 'Antarmuka pemrograman aplikasi')) {
                $body = json_decode($body, true);

                $phrase = Word::firstOrNew(['phrase' => $body['kateglo']['phrase']])
                    ->fill(['metadata' => $body['kateglo']]);
                $phrase->save();

                foreach ($body['kateglo']['definition'] as $definition) {
                    Definition::firstOrCreate([
                        'word_id' => $phrase->id,
                        'text' => $definition['def_text'] ?? '',
                    ]);
                }
            }
        }
    }
}
