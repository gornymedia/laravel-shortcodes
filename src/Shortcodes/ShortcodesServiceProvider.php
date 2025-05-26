<?php

namespace Gornymedia\Shortcodes;

use Illuminate\Support\ServiceProvider;
use Gornymedia\Shortcodes\Illuminate\View\Factory;

class ShortcodesServiceProvider extends ServiceProvider {

    /**
     * Register the service provider
     */
    public function register()
    {
        $this->registerShortcode();
        $this->registerView();    
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/gornymedia-laravel-shortcodes.php',
            'gornmymedia-laravel-shortcodes'
        );
    }
    /**
     * Boot methods
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/gornymedia-laravel-shortcodes.php' => config_path('gornymedia-laravel-shortcodes.php'),
        ]);
    }

    /**
     * Register the Shortcode
     */
    protected function registerShortcode()
    {
        $this->app->singleton('shortcode', function($app) {
            return new Shortcode();
        });
    }

    /**
     * Register the View
     */
    protected function registerView()
    {
        $this->app->singleton('view', function($app) {
            $resolver   = $app['view.engine.resolver'];
            $finder     = $app['view.finder'];

            $env        = new Factory($resolver, $finder, $app['events'], $app['shortcode']);

            $env->setContainer($app);
            $env->share('app', $app);

            return $env;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'shortcode',
            'view'
        ];
    }

}
