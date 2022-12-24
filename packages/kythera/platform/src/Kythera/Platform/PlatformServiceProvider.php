<?php namespace Kythera\Platform;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Kythera\Router\Router;

class PlatformServiceProvider extends ServiceProvider
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
				if ($this->app->runningInConsole()) {
						$this->commands([
								\Kythera\Commands\ExportNicknames::class,
								\Kythera\Commands\KytheraFixContent::class,
								\Kythera\Commands\KytheraHydrate::class,
								\Kythera\Commands\KytheraImport::class,
								\Kythera\Commands\KytheraImportAudio::class,
								\Kythera\Commands\KytheraImportCategories::class,
								\Kythera\Commands\KytheraImportComments::class,
								\Kythera\Commands\KytheraImportDocuments::class,
								\Kythera\Commands\KytheraImportEntities::class,
								\Kythera\Commands\KytheraImportFamily::class,
								\Kythera\Commands\KytheraImportImages::class,
								\Kythera\Commands\KytheraImportTable::class,
								\Kythera\Commands\KytheraImportUsers::class,
						]);
				}
		}

		/**
		 * Register the service provider.
		 *
		 * @return void
		 */
		public function register()
		{
		    $this->registerRouter();

		    // $this->registerHtml();
				//
		    // $this->registerHtmlMenu();
		}


		public function registerRouter()
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

		    $loader = AliasLoader::getInstance();
		    $loader->alias('Router', 'Kythera\Router\Facades\Router');
		}


		protected function registerHtml()
		{
				$this->app->singleton('xhtml', function($app)
				{
					return new Html($app['url']);
				});

			  $loader = AliasLoader::getInstance();
			  $loader->alias('xhtml', 'Kythera\Html\Facades\Html');
		}


		protected function registerHTmlMenu()
		{
				$this->app->singleton('xmenu', function($app)
				{
					return new HtmlMenu($app['router']);
				});

		    $loader = AliasLoader::getInstance();
		    $loader->alias('xmenu', 'Kythera\Html\Facades\Menu');
		}


		/**
		 * Get the services provided by the provider.
		 *
		 * @return array
		 */
		public function provides()
		{
				//return array('router', 'xhtml', 'xmenu');
				return array('router');
		}

}
