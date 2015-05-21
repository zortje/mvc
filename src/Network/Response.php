<?php

namespace Zortje\MVC\Network;

/**
 * Class Response
 *
 * @package Zortje\MVC\Network
 */
class Response {

	/**
	 * @var array HTTP headers
	 */
	protected $headers = [];

	/**
	 * @var string Output
	 */
	protected $output;

	public function output() {

		// @todo Best way to set headers in a testable way?

		return $this->output;
	}

	/**
	 * @param array  $headers
	 * @param string $output
	 */
	public function __construct($headers, $output) {
		$this->headers = $headers;
		$this->output  = $output;
	}

}
