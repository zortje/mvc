<?php

namespace Zortje\MVC\Model\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class InvalidValueTypeForEntityPropertyException
 *
 * @package Zortje\MVC\Model\Exception
 */
class InvalidValueTypeForEntityPropertyException extends Exception {

	/**
	 * {@inheritdoc}
	 */
	protected $template = 'Entity %s property %s is of type %s and not %s';

	/**
	 * {@inheritdoc}
	 */
	public function __construct($message) {
		parent::__construct($message);
	}

}
