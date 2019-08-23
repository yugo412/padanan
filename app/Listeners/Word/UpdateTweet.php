<?php

namespace App\Listeners\Word;

use App\Events\Word\StoredEvent;
use App\Facades\Twitter;
use App\Models\Term;
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
            'origin' => $event->term->origin,
            'locale' => $event->term->locale,
            'category' => strtolower($event->term->category->name),
            'user' => data_get(Auth::user(), 'name', __('Anonim')),
            'line' => str_repeat(PHP_EOL, 2),
        ];

        $templates = [
            __('Padanan dalam bidang :category baru saja ditambahkan oleh :user:line:origin = :locale:line#padanan #glosarium', $replaces),
            __(':user baru saja menambahkan padanan baru dalam bidang :category:line:origin = :locale:line#padanan #glosarium', $replaces),
            __('Padanan baru dalam bidang :category: :origin = :locale.:lineTerima kasih :user telah berkontribusi di #padanan #glosarium.', $replaces),
            __('Sumbangan dari :user, istilah baru telah ditambahkan.:line:origin = :locale (:category):line#padanan #glosarium', $replaces),
        ];

        Twitter::send(collect($templates)->random());
    }
}
