<?php

namespace Zortje\MVC\Routing;

use Zortje\MVC\Controller\ControllerFactory;
use Zortje\MVC\Controller\Exception\ControllerNonexistentException;
use Zortje\MVC\Controller\Exception\ControllerInvalidSuperclassException;
use Zortje\MVC\Controller\Exception\ControllerActionProtectedInsufficientAuthenticationException;
use Zortje\MVC\Controller\Exception\ControllerActionPrivateInsufficientAuthenticationException;
use Zortje\MVC\Controller\Exception\ControllerActionNonexistentException;
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

		/**
		 * Validate and initialize controller
		 */
		$controllerFactory = new ControllerFactory($pdo, $user);

		try {
			$controllerFactory = new ControllerFactory();

			$controller = $controllerFactory->create($controller);
		} catch (\Exception $e) {
			if ($e instanceof ControllerNonexistentException || $e instanceof ControllerInvalidSuperclassException) {
				// @todo Log nonexistent controller
				// @todo Log invalid superclass

				$controller = $controllerFactory->create('NotFound');
			} else {
				throw $e;
			}
		}


		try {

			// Check if controller implements the action
			// Check if user is properly authenticated for that action

			$controller->prepareAction($action);

		} catch (ControllerActionProtectedInsufficientAuthenticationException $e) {
			// @todo Log unauthed protected controller action attempt

			// redirect to login page & and save what action was requested to redirect after successful login
		} catch (ControllerActionPrivateInsufficientAuthenticationException $e) {
			// @todo Log unauthed private controller action attempt

			$controller = $controllerFactory->create('NotFound');
		} catch (ControllerActionNonexistentException $e) {
			// @todo Log nonexistent controller action

			$controller = $controllerFactory->create('NotFound');
		}


		// The content type is decided by the controller and is sent along in the $headers array
		// Request type could be:
		//
		// text/html
		// application/javascript

		list($headers, $output) = $controller->callAction();


		$response = new Response($headers, $output);

		return $response;
	}

	/**
	 * @param Router $router
	 */
	public function __construct(Router $router) {
		$this->router = $router;
	}

}
