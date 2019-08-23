<?php

namespace App\Services;

use Sastrawi\Stemmer\StemmerFactory;

class Sastrawi
{
    private $stemmer;

    public function __construct()
    {
        $factory = new StemmerFactory;
        $this->stemmer = $factory->createStemmer(app()->environment('locale'));
    }

    /**
     * @param string $sentence
     * @return string
     */
    public function stem(string $sentence): string
    {
        return $this->stemmer->stem($sentence);
    }
}
