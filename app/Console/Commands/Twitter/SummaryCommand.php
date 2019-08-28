<?php

namespace App\Console\Commands\Twitter;

use App\Facades\Twitter;
use App\Models\Term;
use Illuminate\Console\Command;

class SummaryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twitter:summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly summary to Twitter';

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
        $locale = 'id_ID';

        $termCount = Term::count();

        $lastWeek = now()->subWeek();
        $newTermCount = Term::whereDate('created_at', '>=', $lastWeek->format('Y-m-d'))
            ->count();

        $number = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
        $countable = new \NumberFormatter($locale, \NumberFormatter::CURRENCY_CODE);

        $line = str_repeat(PHP_EOL, 2);

        $link = route('summary.weekly', ['sub' => 1]);

        if ($newTermCount >= 1) {
            $template = __(':Word_count (:new_count) istilah & padanan baru berhasil ditambahkan minggu lalu. Total :count istilah asing dan padanannya tersimpan di pangkalan data :app.:lineTerima kasih pengguna & kontributor. 🤗:line:link', [
                'word_count' => $countable->format($newTermCount),
                'new_count' => $number->format($newTermCount),
                'count' => $number->format($termCount),
                'app' => config('app.name'),
                'line' => $line,
                'link' => $link,
            ]);
        }
        else {
            $template = __(':Word_count (:count) istilah asing dan padanannya tersimpan di pangkalan data :app.:lineTerima kasih pengguna & kontributor.🤗 :line:link🤗', [
                'word_count' => $countable->format($termCount),
                'count' => $number->format($termCount),
                'app' => config('app.name'),
                'line' => $line,
                'link' => $link,
            ]);
        }

        if (Twitter::auth()) {
            Twitter::send($template);
        }
    }
}
