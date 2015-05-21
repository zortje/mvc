<?php

namespace Zortje\MVC\Tests\Controller\Exception;

use Zortje\MVC\Controller\Exception\ControllerActionProtectedInsufficientAuthenticationException;

/**
 * Class ControllerActionProtectedInsufficientAuthenticationExceptionTest
 *
 * @package            Zortje\MVC\Tests\Controller\Exception
 *
 * @coversDefaultClass Zortje\MVC\Controller\Exception\ControllerActionProtectedInsufficientAuthenticationException
 */
class ControllerActionProtectedInsufficientAuthenticationExceptionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::__construct
	 *
	 * @expectedException Zortje\MVC\Controller\Exception\ControllerActionProtectedInsufficientAuthenticationException
	 * @expectedExceptionMessage foo
	 */
	public function testMessage() {
		throw new ControllerActionProtectedInsufficientAuthenticationException('foo');
	}

	/**
	 * @covers ::__construct
	 *
	 * @expectedException Zortje\MVC\Controller\Exception\ControllerActionProtectedInsufficientAuthenticationException
	 * @expectedExceptionMessage Controller Foo protected action bar requires authentication
	 */
	public function testMessageArray() {
		throw new ControllerActionProtectedInsufficientAuthenticationException(['Foo', 'bar']);
	}

}
