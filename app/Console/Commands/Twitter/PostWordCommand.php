<?php

namespace App\Console\Commands\Twitter;

use App\Facades\Twitter;
use App\Models\Word;
use Illuminate\Console\Command;

class PostWordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twitter:word:random';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Post a random word to Twitter';

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
        $word = Word::inRandomOrder()
            ->first();

        if (!empty($word)) {
            $replaces = [
                ':origin' => $word->origin,
                ':locale' => $word->locale,
                ':category' => $word->category->name,
            ];

            $line = str_repeat(PHP_EOL, 2);

            $templates = [
                sprintf('Padanan :origin dalam :category adalah :locale. #padanan #glosarium'),
                sprintf('Padanan dalam bidang :category %s :origin = :locale %s #padanan #glosarium', $line, $line),
                sprintf('Dalam bahasa Indonesia, :origin berarti :locale. #padanan #glosarium'),
            ];

            Twitter::send(str_replace(array_keys($replaces), array_values($replaces), collect($templates)->random()));
        }
    }
}
