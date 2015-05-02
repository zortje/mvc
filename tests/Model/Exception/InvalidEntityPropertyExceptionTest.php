<?php

namespace Zortje\MVC\Tests\Model\Exception;

use Zortje\MVC\Model\Exception\InvalidEntityPropertyException;

/**
 * Class InvalidEntityPropertyExceptionTest
 *
 * @package            Zortje\MVC\Tests\Model\Exception
 *
 * @coversDefaultClass Zortje\MVC\Model\Exception\InvalidEntityPropertyException
 */
class InvalidEntityPropertyExceptionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::__construct
	 *
	 * @expectedException Zortje\MVC\Model\Exception\InvalidEntityPropertyException
	 * @expectedExceptionMessage foo
	 */
	public function testMessage() {
		throw new InvalidEntityPropertyException('foo');
	}

	/**
	 * @covers ::__construct
	 *
	 * @expectedException Zortje\MVC\Model\Exception\InvalidEntityPropertyException
	 * @expectedExceptionMessage Entity Foo does not have a property named bar
	 */
	public function testMessageArray() {
		throw new InvalidEntityPropertyException(['Foo', 'bar']);
	}

}
