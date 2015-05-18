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
		try {

			$controller = $this->initializeController($controller);

		}
		catch (\Exception $e) {
			if ($e instanceof ControllerNonexistentException || $e instanceof ControllerInvalidSuperclassException) {
				// @todo Log nonexistent controller

				$controller = new NotFoundController();
			}
			else {
				throw $e;
			}
		}



		// Check if controller implements the action

		// Check if user is properly authenticated for that action

		try {

			$controller->prepareAction($action, $user, $pdo);

		} catch (\Exception $e) {
			if ($e instanceof ControllerActionProtectedInsufficientAuthenticationException) {
				// @todo Log unauthed protected controller action attempt

				// redirect to login page & and save what action was requested to redirect after successful login
			} elseif ($e instanceof ControllerActionPrivateInsufficientAuthenticationException) {
				// @todo Log unauthed private controller action attempt

				$controller = new NotFoundController();
			} elseif ($e instanceof ControllerActionNonexistentException) {
				// @todo Log nonexistent controller action

				$controller = new NotFoundController();
			} else {
				throw $e;
			}
		}







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
