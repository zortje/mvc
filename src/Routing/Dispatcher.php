<?php

namespace Zortje\MVC\Routing;

use Zortje\MVC\Network\Request;
use Zortje\MVC\Network\Response;

/**
 * Class Dispatcher
 *
 * @package Zortje\MVC\Routing
 */
class Dispatcher {

	/**
	 * @var Router
	 */
	protected $router;

	public function dispatch(Request $request) {

		// @todo

		list($controller, $action) = $this->router->route($request->getUrlPath());

		// Check if controller exists

		// Check if controller implements the action



		return new Response();
	}

	/**
	 * @param Router $router
	 */
	public function __construct(Router $router) {
		$this->router = $router;
	}

}
