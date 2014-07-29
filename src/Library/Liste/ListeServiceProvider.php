<?php
namespace Ipsum\Core\Library\Liste;

use Illuminate\Support\ServiceProvider;

class ListeServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bindShared('liste', function($app)
		{
			return new Liste($app['request']);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('liste');
	}

}
