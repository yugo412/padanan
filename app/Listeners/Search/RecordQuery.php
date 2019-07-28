<?php

namespace App\Listeners\Search;

use App\Models\Search;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class RecordQuery
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
    public function handle($event)
    {
        Search::create([
            'user_id' => Auth::id(),
            'query' => $event->keyword,
            'metadata' => [
                'results_count' => $event->words->total(),
            ],
        ]);
    }
}
