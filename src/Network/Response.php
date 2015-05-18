<?php

namespace Zortje\MVC\Network;

/**
 * Class Response
 *
 * @package Zortje\MVC\Network
 */
class Response {

	protected $headers = [];
	protected $output;

	public function output() {

		// @todo Best way to set headers in a testable way?

		return $this->output;
	}

}
