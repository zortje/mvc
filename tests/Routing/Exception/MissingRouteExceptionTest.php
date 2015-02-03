<?php

namespace Zortje\MVC\Tests\Routing\Exception;

use Zortje\MVC\Routing\Exception\MissingRouteException;

/**
 * Class MissingRouteExceptionTest
 *
 * @package Zortje\MVC\Tests\Routing\Exception
 */
class MissingRouteExceptionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @expectedException Zortje\MVC\Routing\Exception\MissingRouteException
	 * @expectedExceptionMessage foo
	 */
	public function testMessage() {
		throw new MissingRouteException('foo');
	}

	/**
	 * @expectedException Zortje\MVC\Routing\Exception\MissingRouteException
	 * @expectedExceptionMessage Route foo is not connected
	 */
	public function testMessageArray() {
		throw new MissingRouteException(['foo']);
	}
}
