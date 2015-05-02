<?php

namespace Zortje\MVC\Tests\Model\Exception;

use Zortje\MVC\Model\Exception\EntityClassNonexistentException;

/**
 * Class EntityClassNonexistentExceptionTest
 *
 * @package            Zortje\MVC\Tests\Model\Exception
 *
 * @coversDefaultClass Zortje\MVC\Model\Exception\EntityClassNonexistentException
 */
class EntityClassNonexistentExceptionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::__construct
	 *
	 * @expectedException Zortje\MVC\Model\Exception\EntityClassNonexistentException
	 * @expectedExceptionMessage foo
	 */
	public function testMessage() {
		throw new EntityClassNonexistentException('foo');
	}

	/**
	 * @covers ::__construct
	 *
	 * @expectedException Zortje\MVC\Model\Exception\EntityClassNonexistentException
	 * @expectedExceptionMessage Subclass Foo defined entity class Bar is nonexistent
	 */
	public function testMessageArray() {
		throw new EntityClassNonexistentException(['Foo', 'Bar']);
	}

}
