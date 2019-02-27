<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TrelloApi;

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
            return new TrelloApi(config('app.trello_key'));
        });
    }
}
