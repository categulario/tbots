<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Bots\BotManager;

class BotServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(BotManager::class, function($app) {
            return new BotManager;
        });
    }
}
