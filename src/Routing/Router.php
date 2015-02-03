<?php

namespace Zortje\MVC\Routing;

/**
 * Class Router
 *
 * @package Zortje\MVC\Routing
 */
class Router {

	/**
	 * @var array Routes
	 */
	private $routes = [];

	/**
	 * Connects a new route in the router
	 *
	 * @param string $route      Route
	 * @param string $controller Controller
	 * @param string $action     Action
	 */
	public function connect($route, $controller, $action) {
		if (isset($this->routes[$route]) === true) {
			throw new \InvalidArgumentException('Route is already connected');
		}

		$this->routes[$route] = [
			'controller' => $controller,
			'action'     => $action
		];
	}

	/**
	 * Route to get controller and action
	 *
	 * @param string $route Route
	 *
	 * @return array Controller and action
	 */
	public function route($route) {
		if (isset($this->routes[$route]) === false) {
			throw new \InvalidArgumentException('Route is not connected');
		}

		$result = $this->routes[$route];

		return $result;
	}
}
