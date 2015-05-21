<?php

namespace Zortje\MVC\Tests\Network;

use Zortje\MVC\Network\Request;

/**
 * Class RequestTest
 *
 * @package Zortje\MVC\Tests\Network
 */
class RequestTest extends \PHPUnit_Framework_TestCase {

	public function testGetPath() {
		$request = new Request('https://www.example.com/cars', []);

		$this->assertEquals('/cars', $request->getPath());
	}

}
