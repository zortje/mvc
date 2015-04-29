<?php

namespace Zortje\MVC\Tests\Routing\Exception;

use Zortje\MVC\Routing\Exception\MissingRouteException;

/**
 * Class MissingRouteExceptionTest
 *
 * @package            Zortje\MVC\Tests\Routing\Exception
 *
 * @coversDefaultClass Zortje\MVC\Routing\Exception\MissingRouteException
 */
class MissingRouteExceptionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::__construct
	 *
	 * @expectedException Zortje\MVC\Routing\Exception\MissingRouteException
	 * @expectedExceptionMessage foo
	 */
	public function testMessage() {
		throw new MissingRouteException('foo');
	}

	/**
	 * @covers ::__construct
	 *
	 * @expectedException Zortje\MVC\Routing\Exception\MissingRouteException
	 * @expectedExceptionMessage Route /foo is not connected
	 */
	public function testMessageArray() {
		throw new MissingRouteException(['/foo']);
	}
}
