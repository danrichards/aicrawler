<?php

namespace Dan\AiCrawler\Providers;

use Illuminate\Support\ServiceProvider;

class AiCrawlerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../../../config/aicrawler.php' => config_path('aicrawler.php'),
        ]);
        $this->publishes([
            __DIR__.'/../../../../config/aicrawler.heuristics.blog.php' => config_path('aicrawler.heuristics.blog.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('aicrawler', function ($app) {
            return new AiCrawler();
        });
    }
}
