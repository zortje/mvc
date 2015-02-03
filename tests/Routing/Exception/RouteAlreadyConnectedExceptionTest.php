<?php

namespace Zortje\MVC\Tests\Routing\Exception;

use Zortje\MVC\Routing\Exception\RouteAlreadyConnectedException;

/**
 * Class RouteAlreadyConnectedExceptionTest
 *
 * @package Zortje\MVC\Tests\Routing\Exception
 */
class RouteAlreadyConnectedExceptionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @expectedException Zortje\MVC\Routing\Exception\RouteAlreadyConnectedException
	 * @expectedExceptionMessage foo
	 */
	public function testMessage() {
		throw new RouteAlreadyConnectedException('foo');
	}

	/**
	 * @expectedException Zortje\MVC\Routing\Exception\RouteAlreadyConnectedException
	 * @expectedExceptionMessage Route foo is already connected
	 */
	public function testMessageArray() {
		throw new RouteAlreadyConnectedException(['foo']);
	}
}
