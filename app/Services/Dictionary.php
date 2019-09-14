<?php

namespace App\Services;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Str;
use function GuzzleHttp\Psr7\str;

class Dictionary
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $guzzle = new GuzzleClient([
//            'base_uri' => 'https://kbbi.kemdikbud.go.id/entri/',
            'timeout' => 5,
        ]);

        $this->client = new Client;
        $this->client->setClient($guzzle);
    }

    /**
     * @param string $word
     * @return array|null
     */
    public function explain(string $word): ?array
    {
        $crawler = $this->client->request('GET', 'https://kbbi.kemdikbud.go.id/entri/' . $word);

        $heading = $crawler->filter('div.body-content > h2')->first();

        $descriptions = $crawler->filter('div.body-content > ol > li')->each(function ($node) use (&$descriptions) {
            $types = [];
            $node->filter('font[color=red]')->each(function ($node) use (&$types) {
                foreach (explode(' ', $node->text()) as $type) {
                    $types[] = trim($type);
                }
            });

            $types = array_filter($types);

            $delimiter = end($types);

            list($_, $description) = explode($delimiter, $node->text(), 2);
            if (Str::contains($description, ':')) {
                list($description, $example) = explode(':', $description, 2);
                $example = trim(str_replace('--,', null, $example));
            }

            return [
                'types' => array_filter($types),
                'description' => trim($description),
                'example' => $example ?? null,
            ];
        });

        return [
            'word' => $word,
            'heading' => preg_replace('/\d+/', null, $heading->text()),
            'descriptions' => $descriptions,
        ];
    }
}
