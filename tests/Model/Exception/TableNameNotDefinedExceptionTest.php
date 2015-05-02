<?php

namespace Zortje\MVC\Tests\Model\Exception;

use Zortje\MVC\Model\Exception\TableNameNotDefinedException;

/**
 * Class TableNameNotDefinedExceptionTest
 *
 * @package            Zortje\MVC\Tests\Model\Exception
 *
 * @coversDefaultClass Zortje\MVC\Model\Exception\TableNameNotDefinedException
 */
class TableNameNotDefinedExceptionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::__construct
	 *
	 * @expectedException Zortje\MVC\Model\Exception\TableNameNotDefinedException
	 * @expectedExceptionMessage foo
	 */
	public function testMessage() {
		throw new TableNameNotDefinedException('foo');
	}

	/**
	 * @covers ::__construct
	 *
	 * @expectedException Zortje\MVC\Model\Exception\TableNameNotDefinedException
	 * @expectedExceptionMessage Subclass Foo does not have a table name defined
	 */
	public function testMessageArray() {
		throw new TableNameNotDefinedException(['Foo']);
	}

}
