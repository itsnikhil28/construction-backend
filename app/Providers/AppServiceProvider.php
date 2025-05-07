<?php

namespace App\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }

        $loader = AliasLoader::getInstance();
        // using custom PersonalAccessToken model instead of Sanctum's
        $loader->alias(\Laravel\Sanctum\PersonalAccessToken::class, \App\Models\PersonalAccessToken::class);
    }
}
