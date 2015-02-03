<?php

namespace Zortje\MVC\Tests\Common\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class ExceptionTest
 *
 * @package Zortje\MVC\Tests\Common\Exception
 */
class ExceptionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @expectedException Zortje\MVC\Common\Exception\Exception
	 * @expectedExceptionMessage foo
	 * @expectedExceptionCode    0
	 */
	public function testMessage() {
		throw new Exception('foo');
	}

	/**
	 * @expectedException Zortje\MVC\Common\Exception\Exception
	 * @expectedExceptionMessage
	 * @expectedExceptionCode 0
	 */
	public function testMessageArray() {
		throw new Exception(['foo']);
	}

	/**
	 * @expectedException Zortje\MVC\Common\Exception\Exception
	 * @expectedExceptionMessage
	 * @expectedExceptionCode 42
	 */
	public function testCode() {
		throw new Exception(null, 42);
	}
}
