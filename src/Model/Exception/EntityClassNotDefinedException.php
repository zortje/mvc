<?php

namespace Zortje\MVC\Model\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class EntityClassNotDefinedException
 *
 * @package Zortje\MVC\Model\Exception
 */
class EntityClassNotDefinedException extends Exception {

	/**
	 * {@inheritdoc}
	 */
	protected $template = 'Subclass %s does not have a entity class defined';

	/**
	 * {@inheritdoc}
	 */
	public function __construct($message) {
		parent::__construct($message);
	}

}
