<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
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
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['id','en'])
                ->visible(outsidePanels: true)
                ->flags([
                    'id' => asset('img/icon/id.png'),
                    'en' => asset('img/icon/en.png'),
                ])
                ->circular(); // also accepts a closure
        });
    }
}
