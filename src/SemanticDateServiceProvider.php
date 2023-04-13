<?php

namespace Haltsir\SemanticDate;

use Illuminate\Support\ServiceProvider;

class SemanticDateServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish the config file
        $this->publishes([
            __DIR__ . '/../config/semantic-date.php' => config_path('semantic-date.php'),
        ], 'semantic-date-config');

        // Publish the language files
        $this->publishes([
            __DIR__ . '/../lang' => resource_path('lang/vendor/semantic-date'),
        ], 'semantic-date-lang');
    }

    public function register()
    {
        $this->app->singleton('semantic-date', function ($app) {
            $configuration = $app['config']->get('semantic-date', []);
            $locale = $app->getLocale();

            return new DateConverter();
        });
    }
}
