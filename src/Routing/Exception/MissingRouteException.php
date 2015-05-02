<?php

namespace Zortje\MVC\Routing\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class MissingRouteException
 *
 * @package Zortje\MVC\Routing\Exception
 */
class MissingRouteException extends Exception {

	/**
	 * {@inheritdoc}
	 */
	protected $template = 'Route %s is not connected';

	/**
	 * {@inheritdoc}
	 */
	public function __construct($message) {
		parent::__construct($message);
	}

}
