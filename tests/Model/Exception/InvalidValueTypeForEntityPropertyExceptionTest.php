<?php

namespace Zortje\MVC\Tests\Model\Exception;

use Zortje\MVC\Model\Exception\InvalidValueTypeForEntityPropertyException;

/**
 * Class InvalidValueTypeForEntityPropertyExceptionTest
 *
 * @package            Zortje\MVC\Tests\Model\Exception
 *
 * @coversDefaultClass Zortje\MVC\Model\Exception\InvalidValueTypeForEntityPropertyException
 */
class InvalidValueTypeForEntityPropertyExceptionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::__construct
	 *
	 * @expectedException Zortje\MVC\Model\Exception\InvalidValueTypeForEntityPropertyException
	 * @expectedExceptionMessage foo
	 */
	public function testMessage() {
		throw new InvalidValueTypeForEntityPropertyException('foo');
	}

	/**
	 * @covers ::__construct
	 *
	 * @expectedException Zortje\MVC\Model\Exception\InvalidValueTypeForEntityPropertyException
	 * @expectedExceptionMessage Entity Foo property bar is of type string and not integer
	 */
	public function testMessageArray() {
		throw new InvalidValueTypeForEntityPropertyException(['Foo', 'bar', 'string', 'integer']);
	}

}
