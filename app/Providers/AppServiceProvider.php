<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('twitter', function (){
            return new \App\Services\Twitter;
        });

        App::bind('sastrawi', function () {
            return new \App\Services\Sastrawi;
        });

        App::bind('dictionary', function () {
            return new \App\Services\Dictionary;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Relation::morphMap([
            'term' => \App\Models\Term::class,
        ]);
    }
}
