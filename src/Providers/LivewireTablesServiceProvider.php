<?php

namespace Netflex\Livewire\Tables\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class LivewireTablesServiceProvider.
 */
class LivewireTablesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'netflex-livewire-tables');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'netflex-livewire-tables');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../resources/views' => resource_path('views/vendor/netflex-livewire-tables'),
            ], 'views');

            $this->publishes([
                __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/netflex-livewire-tables'),
            ], 'lang');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }
}
