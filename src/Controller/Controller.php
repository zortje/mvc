<?php

namespace Zortje\MVC\Controller;

use Zortje\MVC\Model\User;
use Zortje\MVC\Controller\Exception\ControllerActionNonexistentException;
use Zortje\MVC\Controller\Exception\ControllerActionPrivateInsufficientAuthenticationException;
use Zortje\MVC\Controller\Exception\ControllerActionProtectedInsufficientAuthenticationException;

/**
 * Class Controller
 *
 * @package Zortje\MVC\Controller
 */
class Controller {

	/**
	 * Controller action is publicly accessible
	 */
	const ACTION_PUBLIC = 0;

	/**
	 * Controller action requires authentication
	 * Will redirect to login page if not authenticated
	 */
	const ACTION_PROTECTED = 1;

	/**
	 * Controller action requires authentication
	 * Will result in an 404 if not authenticated
	 */
	const ACTION_PRIVATE = 2;

	/**
	 * @var array Controller action access rules
	 */
	protected $access = [];

	/**
	 * @var \PDO PDO
	 */
	protected $pdo;

	/**
	 * @var null|User User
	 */
	protected $user;

	/**
	 * @var string Controller action
	 */
	protected $action;

	/**
	 * @var array View variables
	 */
	protected $variables;

	/**
	 * @param string $action Controller action
	 *
	 * @throws ControllerActionNonexistentException
	 * @throws ControllerActionPrivateInsufficientAuthenticationException
	 * @throws ControllerActionProtectedInsufficientAuthenticationException
	 */
	public function setAction($action) {
		/**
		 * Check if method exists and that access has been defined
		 */
		if (!method_exists($this, $action) || !isset($this->access[$action])) {
			throw new ControllerActionNonexistentException([get_class($this), $action]);
		}

		/**
		 * Check controller action access level if user is not authenticated
		 */
		if (!$this->user) {
			if ($this->access[$action] === self::ACTION_PRIVATE) {
				throw new ControllerActionPrivateInsufficientAuthenticationException([get_class($this), $action]);
			} elseif ($this->access[$action] === self::ACTION_PROTECTED) {
				throw new ControllerActionProtectedInsufficientAuthenticationException([get_class($this), $action]);
			}
		}

		/**
		 * Set controller action
		 */
		$this->action = $action;
	}

	public function callAction() {
		// The content type is decided by the controller and is sent along in the $headers array
		// Request type could be:
		//
		// text/html
		// application/javascript

		// @todo throw exception is action is not set
	}

	/**
	 * Set view variable
	 *
	 * @param string $variable
	 * @param mixed  $value
	 */
	protected function set($variable, $value) {
		$this->variables[$variable] = $value;
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
