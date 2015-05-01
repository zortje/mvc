<?php

namespace Zortje\MVC\Model\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class TableNotDefinedException
 *
 * @package Zortje\MVC\Routing\Exception
 */
class TableNotDefinedException extends Exception {

	/**
	 * {@inheritdoc}
	 */
	protected $template = 'Table name is not set';

	/**
	 * {@inheritdoc}
	 */
	public function __construct() {
		parent::__construct(null);
	}
}
