<?php

namespace Zortje\MVC\Routing;

use Zortje\MVC\Network\Request;
use Zortje\MVC\Network\Response;
use Zortje\MVC\Controller\Controller;

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

		/**
		 * Validate and initialize controller
		 */
		$controller = $this->initializeController($controller);


		// Check if controller implements the action


		return new Response();
	}

	/**
	 * Initialize controller
	 *
	 * @param string $controller Controller class name
	 *
	 * @return Controller Controller object
	 * @throws ControllerNonexistentException If controller class is nonexistent
	 * @throws ControllerInvalidSuperclassException If controller class is not subclass of base controller
	 */
	protected function initializeController($controller) {
		if (!class_exists($controller)) {
			throw new ControllerNonexistentException($controller);
		} else if (!is_subclass_of($controller, Controller::class)) {
			throw new ControllerInvalidSuperclassException($controller);
		}

		$controller = new $controller;

		return $controller;
	}

	/**
	 * @param Router $router
	 */
	public function __construct(Router $router) {
		$this->router = $router;
	}

}
