<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        \App\Events\Word\StoredEvent::class => [
            \App\Listeners\Word\UpdateTweet::class,
        ],

        \App\Events\Word\SearchEvent::class => [
            \App\Listeners\Search\RecordQuery::class,
        ],

        \App\Events\Term\TermViewed::class => [
//            \App\Listeners\Term\TermDefinition::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
