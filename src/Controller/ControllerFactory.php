<?php

namespace Zortje\MVC\Controller;

use Zortje\MVC\Controller\Exception\ControllerNonexistentException;
use Zortje\MVC\Controller\Exception\ControllerInvalidSuperclassException;

/**
 * Class ControllerFactory
 *
 * @package Zortje\MVC\Controller
 */
class ControllerFactory {

	/**
	 * Initialize controller
	 *
	 * @param string $controller Controller class name
	 *
	 * @return mixed
	 *
	 * @throws ControllerInvalidSuperclassException
	 * @throws ControllerNonexistentException
	 */
	public function create($controller) {
		if (!class_exists($controller)) {
			throw new ControllerNonexistentException($controller);
		} else if (!is_subclass_of($controller, Controller::class)) {
			throw new ControllerInvalidSuperclassException($controller);
		}

		$controller = new $controller;

		return $controller;
	}

}
