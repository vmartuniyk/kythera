<?php namespace Kythera\Router;

use Illuminate\Support\ServiceProvider;

class RouterServiceProvider extends ServiceProvider {

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
		// $this->package('kythera/router');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('router', function($app)
		{
			$router = new Router($app['events'], $app);

			// If the current application environment is "testing", we will disable the
			// routing filters, since they can be tested independently of the routes
			// and just get in the way of our typical controller testing concerns.
			if ($app['env'] == 'testing')
			{
				$router->disableFilters();
			}

			return $router;
		});


        #add alias (or add to app->aliases)
        $this->app->booting(function() {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Router', 'Kythera\Router\Facades\Router');
        });

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('router');
	}

}
