<?php

namespace Zortje\MVC\Tests\Model\Exception;

use Zortje\MVC\Model\Exception\EntityClassNotDefinedException;

/**
 * Class EntityClassNotDefinedExceptionTest
 *
 * @package            Zortje\MVC\Tests\Model\Exception
 *
 * @coversDefaultClass Zortje\MVC\Model\Exception\EntityClassNotDefinedException
 */
class EntityClassNotDefinedExceptionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::__construct
	 *
	 * @expectedException Zortje\MVC\Model\Exception\EntityClassNotDefinedException
	 * @expectedExceptionMessage foo
	 */
	public function testMessage() {
		throw new EntityClassNotDefinedException('foo');
	}

	/**
	 * @covers ::__construct
	 *
	 * @expectedException Zortje\MVC\Model\Exception\EntityClassNotDefinedException
	 * @expectedExceptionMessage Subclass Foo does not have a entity class defined
	 */
	public function testMessageArray() {
		throw new EntityClassNotDefinedException(['Foo']);
	}

}
