<?php
/**
 * Copyright 2015 Spark Eleven llc. All Rights Reserved
 * http://sparkeleven.com
 * MIT License:
 * http://opensource.org/licenses/MIT
 */

/**
 * Namespace
 */
namespace SparkEleven\Potion;

use Illuminate\Support\ServiceProvider;
use SparkEleven\Potion\Console\Command\MakeAssetsCommand;
use SparkEleven\Potion\Console\Command\ClearAssetsCommand;

/**
 * Class PotionServiceProvider
 * @package Classygeeks\Potion
 */
class PotionServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Handle config file
        // -- -- Get path
        $config_file = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

        // -- -- Merge from config
        $this->mergeConfigFrom($config_file, 'potion');

        // -- -- Tell laravel that we publish this file
        $this->publishes([
            $config_file => config_path('potion.php')
        ], 'config');

        // Handle blade extensions
        $this->bladeExtensions();

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Make assets command
        $this->app->bind('potion.make-assets', function() {
            return new MakeAssetsCommand($this->getConfig());
        });

        // Clear assets command
        $this->app->singleton('potion.clear-assets', function() {
            return new ClearAssetsCommand($this->getConfig());
        });

        // Register commands
        $this->commands([
            'potion.make-assets',
            'potion.clear-assets'
        ]);
    }

    /**
     * Get config
     * @return array
     */
    public function getConfig()
    {
        return $this->app['config']['potion'];
    }

    /**
     * Handle blade extensions
     */
    protected function bladeExtensions()
    {
        // Potion asset url
        \Blade::extend(function($view, $compiler)
        {
            /**
             * If the directive method exists, we're using Laravel 5.1+
             */
            if (method_exists($compiler, 'directive')) {
                $pattern = '(potion_asset_url)';
            }
            else {
                $pattern = $compiler->createMatcher('potion_asset_url');
            }
            return preg_replace($pattern, '$1<?php echo(\SparkEleven\Potion\BladeHelpers::assetUrl$2); ?>', $view);
        });

        // Potion Css
        \Blade::extend(function($view, $compiler)
        {
            /**
             * If the directive method exists, we're using Laravel 5.1+
             */
            if (method_exists($compiler, 'directive')) {
                $pattern = '(potion_asset_css)';
            }
            else {
                $pattern = $compiler->createMatcher('potion_asset_css');
            }
            return preg_replace($pattern, '$1<?php echo(\SparkEleven\Potion\BladeHelpers::assetCss$2); ?>', $view);
        });

        // Potion Js
        \Blade::extend(function($view, $compiler)
        {
            /**
             * If the directive method exists, we're using Laravel 5.1+
             */
            if (method_exists($compiler, 'directive')) {
                $pattern = '(potion_asset_js)';
            }
            else {
                $pattern = $compiler->createMatcher('potion_asset_js');
            }
            return preg_replace($pattern, '$1<?php echo(\SparkEleven\Potion\BladeHelpers::assetJs$2); ?>', $view);
        });

        // Potion Img
        \Blade::extend(function($view, $compiler)
        {
            /**
             * If the directive method exists, we're using Laravel 5.1+
             */
            if (method_exists($compiler, 'directive')) {
                $pattern = '(potion_asset_img)';
            }
            else {
                $pattern = $compiler->createMatcher('potion_asset_img');
            }
            return preg_replace($pattern, '$1<?php echo(\SparkEleven\Potion\BladeHelpers::assetImg$2); ?>', $view);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['potion'];
    }

}
