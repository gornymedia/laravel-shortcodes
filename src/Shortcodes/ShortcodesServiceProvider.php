<?php

namespace Gornymedia\Shortcodes;

use Illuminate\Support\ServiceProvider;
use Gornymedia\Shortcodes\Illuminate\View\Factory;

class ShortcodesServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     * 
     * @return void
     */
    public function register() {
        $this->registerShortcode();
        $this->registerView();
    }

    /**
     * Register the Shortcode
     */
    public function registerShortcode() {
        $this->app->bindShared('shortcode', function($app) {
            return new Shortcode();
        });
    }

    /**
     * Register the View
     */
    public function registerView() {
        $this->app->bindShared('view', function($app) {
            $resolver = $app['view.engine.resolver'];
            $finder = $app['view.finder'];
            $env = new Factory($resolver, $finder, $app['events'], $app['shortcode']);
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
    public function provides() {
        return array(
            'shortcode',
            'view'
        );
    }

}
