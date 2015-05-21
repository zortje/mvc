<?php

namespace Zortje\MVC\Controller;

use Zortje\MVC\Controller\Exception\ControllerNonexistentException;
use Zortje\MVC\Controller\Exception\ControllerInvalidSuperclassException;
use Zortje\MVC\Model\User;

/**
 * Class ControllerFactory
 *
 * @package Zortje\MVC\Controller
 */
class ControllerFactory {

	/**
	 * @var \PDO PDO
	 */
	protected $pdo;

	/**
	 * @var null|User User
	 */
	protected $user;

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
			throw new ControllerNonexistentException([$controller]);
		} else if (!is_subclass_of($controller, Controller::class)) {
			throw new ControllerInvalidSuperclassException([$controller]);
		}

		/**
		 * @var Controller $controller
		 */
		$controller = new $controller($this->pdo, $this->user);

		return $controller;
	}

	/**
	 * @param \PDO      $pdo
	 * @param null|User $user
	 */
	public function __construct(\PDO $pdo, User $user = null) {
		$this->pdo  = $pdo;
		$this->user = $user;
	}

}
