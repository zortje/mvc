<?php

namespace Zortje\MVC\Routing;

use Zortje\MVC\Controller\ControllerFactory;
use Zortje\MVC\Controller\NotFoundController;
use Zortje\MVC\Model\User;
use Zortje\MVC\Network\Request;
use Zortje\MVC\Network\Response;
use Zortje\MVC\Controller\Exception\ControllerNonexistentException;
use Zortje\MVC\Controller\Exception\ControllerInvalidSuperclassException;
use Zortje\MVC\Controller\Exception\ControllerActionProtectedInsufficientAuthenticationException;
use Zortje\MVC\Controller\Exception\ControllerActionPrivateInsufficientAuthenticationException;
use Zortje\MVC\Controller\Exception\ControllerActionNonexistentException;
use Zortje\MVC\Routing\Exception\RouteNonexistentException;

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

	/**
	 * @var \PDO PDO
	 */
	protected $pdo;

	/**
	 * @var string App file path
	 */
	protected $appPath;

	/**
	 * @var null|User User
	 */
	protected $user;

	/**
	 * @param Request $request Request object
	 *
	 * @return Response Reponse object
	 *
	 * @throws \Exception If unexpected exception is thrown
	 */
	public function dispatch(Request $request) {
		$controllerFactory = new ControllerFactory($this->pdo, $this->appPath, $this->user);

		try {
			list($controller, $action) = array_values($this->router->route($request->getPath()));

			/**
			 * Validate and initialize controller
			 */
			try {
				$controller = $controllerFactory->create($controller);
			} catch (\Exception $e) {
				if ($e instanceof ControllerNonexistentException || $e instanceof ControllerInvalidSuperclassException) {
					// @todo Log nonexistent controller
					// @todo Log invalid superclass

					$controller = $controllerFactory->create(NotFoundController::class);
					$action     = 'index';
				} else {
					throw $e;
				}
			}
		} catch (RouteNonexistentException $e) {
			$controller = $controllerFactory->create(NotFoundController::class);
			$action     = 'index';
		}

		/**
		 * Validate and set controller action
		 */
		try {
			$controller->setAction($action);
		} catch (ControllerActionProtectedInsufficientAuthenticationException $e) {
			// @todo redirect to login page & and save what action was requested to redirect after successful login

			// @todo Log unauthed protected controller action attempt
		} catch (ControllerActionPrivateInsufficientAuthenticationException $e) {
			// @todo Log unauthed private controller action attempt

			$controller = $controllerFactory->create(NotFoundController::class);
		} catch (ControllerActionNonexistentException $e) {
			// @todo Log nonexistent controller action

			$controller = $controllerFactory->create(NotFoundController::class);
		}

		/**
		 * Create response from controller action headers and output
		 */
		list($headers, $output) = array_values($controller->callAction());

		return new Response($headers, $output);
	}

	/**
	 * @param Router $router
	 * @param \PDO   $pdo
	 * @param string $appPath
	 * @param User   $user
	 */
	public function __construct(Router $router, \PDO $pdo, $appPath, User $user = null) {
		$this->router  = $router;
		$this->pdo     = $pdo;
		$this->appPath = $appPath;
		$this->user    = $user;
	}

}
