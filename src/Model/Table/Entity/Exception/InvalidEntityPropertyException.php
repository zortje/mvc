<?php

namespace Zortje\MVC\Model\Table\Entity\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class InvalidEntityPropertyException
 *
 * @package Zortje\MVC\Model\Table\Entity\Exception
 */
class InvalidEntityPropertyException extends Exception {

	/**
	 * {@inheritdoc}
	 */
	protected $template = 'Entity %s does not have a property named %s';

	/**
	 * {@inheritdoc}
	 */
	public function __construct($message) {
		parent::__construct($message);
	}

}
