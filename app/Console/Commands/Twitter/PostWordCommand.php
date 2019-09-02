<?php

namespace App\Console\Commands\Twitter;

use App\Facades\Twitter;
use App\Models\Term;
use App\Models\Tweet;
use DG\Twitter\Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        $term = Term::inRandomOrder()
            ->whereNotIn('id', function ($query) {
                return $query->select('word_id')->from(with(new Tweet)->getTable());
            })
            ->whereRaw('origin != locale')
            ->take(1)
            ->first();

        if (!empty($term)) {
            $replaces = [
                'origin' => $term->origin,
                'locale' => $term->locale,
                'category' => strtolower($term->category->name),
                'line' => str_repeat(PHP_EOL, 2),
            ];

            $templates = [
                __('Mungkin, sebagian dari kita pernah mendengar istilah :origin, tapi belum tahu padanannya. Istilah ini punya padanan :locale. #padanan #glosarium', $replaces),
                __('Apa padanan dari :origin? :locale jawabannya. #padanan #glosarium', $replaces),
                __('Belum tahu padanan dari :origin? Ini padanannya: :locale (:category). #padanan #glosarium', $replaces),
                __('Apakah kamu tahu padanan dari istilah :origin? Jawabannya adalah :locale (:category). #padanan #glosarium', $replaces),
                __('Istilah :origin dikenal sebagai :locale dalam bahasa Indonesia. #padanan #glosarium', $replaces),
                __('Padanan :origin dalam :category adalah :locale. #padanan #glosarium', $replaces),
                __('Padanan dalam bidang :category:line:origin = :locale.:line#padanan #glosarium', $replaces),
                __('Dalam bahasa Indonesia, :origin berarti :locale. #padanan #glosarium', $replaces),
                __('Tahukah kamu kalo padanan dari istilah :origin dalam bidang :category adalah :locale? #padanan #glosarium', $replaces),
                __('Apa itu :origin?:lineDalam bahasa Indonesia dikenal sebagai :locale (:category).:line#padanan #glosarium', $replaces),
                __(':origin = :locale (:category). #padanan #glosarium', $replaces),
                __('Dalam bahasa Indonesia, istilah :origin dipadankan sebagai :locale (:category). #padanan #glosarium', $replaces),
                __('Kalau dalam bahasa asing disebut :origin, maka dalam bahasa Indonesia disebut :locale. Istilah ini umum ada pada bidang :category. #padanan #glosarium', $replaces),
            ];

            try {
                $tweet = Twitter::send('abc');

                Tweet::firstOrNew(['word_id' => $term->id])
                    ->fill(['metadata' => $tweet])
                    ->save();
            } catch (Exception $e) {
                Log::warning($e->getMessage(), [
                    'term_id' => $term->id,
                    'origin' => $term->origin,
                    'locale' => $term->locale,
                ]);
            }
        }
    }
}
