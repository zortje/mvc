<?php

namespace Zortje\MVC\Routing\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class RouteAlreadyConnectedException
 *
 * @package Zortje\MVC\Routing\Exception
 */
class RouteAlreadyConnectedException extends Exception {

	/**
	 * {@inheritdoc}
	 */
	protected $template = 'Route %s is already connected';

	/**
	 * {@inheritdoc}
	 */
	public function __construct($message) {
		parent::__construct($message);
	}
}
