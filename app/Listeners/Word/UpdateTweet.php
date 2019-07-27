<?php

namespace App\Listeners\Word;

use App\Events\Word\StoredEvent;
use App\Facades\Twitter;
use App\Models\Word;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class UpdateTweet
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(StoredEvent $event)
    {
        $replaces = [
            ':origin' =>  $event->word->origin,
            ':locale' => $event->word->locale,
            ':category' => $event->word->category->name,
            ':user' => data_get(Auth::user(), 'name', __('Anonim')),
        ];

        $line = str_repeat(PHP_EOL, 2);

        $templates = [
            sprintf('Padanan dalam bidang :category baru saja ditambahkan: %s :origin = :locale %s #padanan #glosarium', $line, $line),
            sprintf(':user baru saja menambahkan padanan baru dalam bidang :category %s :origin = :locale %s #padanan #glosarium ', $line, $line),
            sprintf('Padanan baru dalam bidang :category: :origin = :locale. %s Terima kasih :user telah berkontribusi di #padanan #glosarium.', $line),
        ];

        $template = str_replace(array_keys($replaces), array_values($replaces), collect($templates)->random());

        Twitter::send($template);
    }
}
