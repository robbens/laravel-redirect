<?php

namespace Robbens\LaravelRedirect;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Robbens\LaravelRedirect\Contracts\RedirectModelContract;
use Robbens\LaravelRedirect\Models\Redirect;

class LaravelRedirectServiceProvider extends ServiceProvider
{
    /**
     * Create a new service provider instance.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
//         $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/redirects.php' => config_path('redirects.php'),
            ], 'config');

            // Publish migrations
            if (! class_exists('CreateRedirectsTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_redirects_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_redirects_table.php'),
                    // you can add any number of migrations here
                ], 'migrations');
            }
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/redirects.php', 'redirects');

        $this->registerBindings();
    }

    /**
     * @return void
     */
    protected function registerBindings()
    {
        $this->app->bind(RedirectModelContract::class, config('redirects.model') ?? Redirect::class);
        $this->app->alias(RedirectModelContract::class, 'redirect.model');
    }
}
