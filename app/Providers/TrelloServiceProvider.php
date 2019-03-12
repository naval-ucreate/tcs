<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\TrelloHooks;

class TrelloServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('trello',function(){
            return new TrelloHooks(config('app.trello_key'));
        });
    }
}
