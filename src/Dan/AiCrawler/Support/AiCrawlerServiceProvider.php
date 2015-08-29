<?php

namespace Dan\AiCrawler\Support;

use Illuminate\Support\ServiceProvider;

class AiCrawlerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
//    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->bindShared('aicrawler', function ($app) {
//            return new AiCrawler();
//        });
        die ("AiCrawlerServiceProvider");
        $this->app->bindShared('aicrawler', function ($app) {
            return (object) ['confused' => "why this isn't working"];
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
//    public function provides()
//    {
//        return ['Dan\AiCrawler\Support\AiCrawler'];
//    }
}
