<?php

namespace Zortje\MVC\Controller;

use Zortje\MVC\Model\User;

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
	 * @var \PDO PDO
	 */
	protected $pdo;

	/**
	 * @var null|User User
	 */
	protected $user;

	/**
	 * @param string $action Controller action
	 */
	public function setAction($action) {

		// Check if controller implements the action
		// Check if user is properly authenticated for that action


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
	 * @param \PDO      $pdo
	 * @param null|User $user
	 */
	public function __construct(\PDO $pdo, User $user = null) {
		$this->pdo  = $pdo;
		$this->user = $user;
	}

}
