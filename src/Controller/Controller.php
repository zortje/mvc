<?php

namespace Zortje\MVC\Controller;

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

}
