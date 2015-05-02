<?php

namespace Zortje\MVC\Model\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class EntityClassNonexistentException
 *
 * @package Zortje\MVC\Model\Exception
 */
class EntityClassNonexistentException extends Exception {

	/**
	 * {@inheritdoc}
	 */
	protected $template = 'Subclass %s defined entity class %s is nonexistent';

	/**
	 * {@inheritdoc}
	 */
	public function __construct($message) {
		parent::__construct($message);
	}

}
