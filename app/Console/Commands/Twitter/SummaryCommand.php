<?php

namespace App\Console\Commands\Twitter;

use App\Facades\Twitter;
use App\Models\Word;
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
        $wordCount = Word::count();

        $lastWeek = now()->subWeek();
        $newWordCount = Word::whereDate('created_at', '>=', $lastWeek->format('Y-m-d'))
            ->count();

        $number = new \NumberFormatter('id_ID', \NumberFormatter::DECIMAL);

        $line = str_repeat(PHP_EOL, 2);

        $link = route('summary.weekly', ['sub' => 1]);

        if ($newWordCount >= 1) {
            $template = __(':newCount padanan baru berhasil ditambahkan minggu lalu. Total :count istilah asing dan padanannya tersimpan di pangkalan data :app.:lineTerima kasih pengguna & kontributor. 🤗:line:link', [
                'newCount' => $number->format($newWordCount),
                'count' => $number->format($wordCount),
                'app' => config('app.name'),
                'line' => $line,
                'link' => $link,
            ]);
        }
        else {
            $template = __('Total :count istilah asing dan padanannya tersimpan di pangkalan data :app.:lineTerima kasih pengguna & kontributor.🤗 :line:link🤗', [
                'count' => $number->format($wordCount),
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
