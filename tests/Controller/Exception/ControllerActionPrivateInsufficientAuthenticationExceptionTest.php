<?php

namespace Zortje\MVC\Tests\Controller\Exception;

use Zortje\MVC\Controller\Exception\ControllerActionPrivateInsufficientAuthenticationException;

/**
 * Class ControllerActionPrivateInsufficientAuthenticationExceptionTest
 *
 * @package            Zortje\MVC\Tests\Controller\Exception
 *
 * @coversDefaultClass Zortje\MVC\Controller\Exception\ControllerActionNonexistentException
 */
class ControllerActionPrivateInsufficientAuthenticationExceptionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::__construct
	 *
	 * @expectedException Zortje\MVC\Controller\Exception\ControllerActionPrivateInsufficientAuthenticationException
	 * @expectedExceptionMessage foo
	 */
	public function testMessage() {
		throw new ControllerActionPrivateInsufficientAuthenticationException('foo');
	}

	/**
	 * @covers ::__construct
	 *
	 * @expectedException Zortje\MVC\Controller\Exception\ControllerActionPrivateInsufficientAuthenticationException
	 * @expectedExceptionMessage Controller Foo private action bar requires authentication
	 */
	public function testMessageArray() {
		throw new ControllerActionPrivateInsufficientAuthenticationException(['Foo', 'bar']);
	}

}
