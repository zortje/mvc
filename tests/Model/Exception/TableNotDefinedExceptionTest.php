<?php

namespace Zortje\MVC\Tests\Model\Exception;

use Zortje\MVC\Model\Exception\TableNotDefinedException;

/**
 * Class TableNotDefinedExceptionTest
 *
 * @package            Zortje\MVC\Tests\Model\Exception
 *
 * @coversDefaultClass Zortje\MVC\Model\Exception\TableNotDefinedException
 */
class TableNotDefinedExceptionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::__construct
	 *
	 * @expectedException Zortje\MVC\Model\Exception\TableNotDefinedException
	 * @expectedExceptionMessage Table name is not set
	 */
	public function testMessage() {
		throw new TableNotDefinedException();
	}

}
