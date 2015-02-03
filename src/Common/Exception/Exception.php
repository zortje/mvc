<?php

namespace Zortje\MVC\Common\Exception;

/**
 * Class Exception
 *
 * @package Zortje\MVC\Common\Exception
 */
class Exception extends \Exception {

	/**
	 * @var string
	 */
	protected $template = '';

	/**
	 * @param string     $message
	 * @param int        $code
	 * @param \Exception $previous
	 */
	public function __construct($message, $code = 0, \Exception $previous = null) {
		if (is_array($message)) {
			$message = vsprintf($this->template, $message);
		}

		parent::__construct($message, $code, $previous);
	}

}
