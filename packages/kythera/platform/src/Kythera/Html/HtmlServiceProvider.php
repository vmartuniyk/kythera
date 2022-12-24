<?php namespace Kythera\Html;

use Illuminate\Support\ServiceProvider;

class HtmlServiceProvider extends ServiceProvider {

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
		// $this->package('kythera/html');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

	    $this->registerHtml();

	    $this->registerHtmlMenu();

        $this->registerHtmlComment();

        $this->registerHtmlMessage();

        $this->registerHtmlDocument();

    }


	protected function registerHtml() {

		$this->app->singleton('xhtml', function($app)
		{
			return new Html($app['url'], view());
		});


            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('xhtml', 'Kythera\Html\Facades\Html');


	}


	protected function registerHtmlMenu() {

		$this->app->singleton('xmenu', function($app)
		{
			return new HtmlMenu($app['router'], view());
		});


            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('xmenu', 'Kythera\Html\Facades\Menu');


	}


	protected function registerHtmlComment() {

		$this->app->singleton('xcomment', function($app)
		{
			return new HtmlComment($app['url'], view());
		});


            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('xcomment', 'Kythera\Html\Facades\Comment');


	}


	protected function registerHtmlMessage() {

		$this->app->singleton('xmessage', function($app)
		{
			return new HtmlMessage($app['url'], view());
		});


            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('xmessage', 'Kythera\Html\Facades\Message');


	}


	protected function registerHtmlDocument()
	{

		$this->app->singleton('xdocument', function($app)
		{
			return new HtmlDocument($app['url'], view());
		});


            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('xdocument', 'Kythera\Html\Facades\Document');

	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('xhtml', 'xmenu', 'xcomment', 'xmessage', 'xdocument');
	}

}
