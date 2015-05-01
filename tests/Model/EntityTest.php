<?php

namespace Zortje\MVC\Tests\Model;

use Zortje\MVC\Tests\Model\Fixture\CarEntity;

/**
 * Class EntityTest
 *
 * @package            Zortje\MVC\Tests\Model
 *
 * @coversDefaultClass Zortje\MVC\Model\Entity
 */
class EntityTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::setId
	 * @covers ::getId
	 */
	public function testId() {
		$carEntity = new CarEntity(null, null);

		$this->assertSame(null, $carEntity->getId());

		$carEntity->setId(42);

		$this->assertSame(42, $carEntity->getId());
	}

	/**
	 * @covers ::setModified
	 * @covers ::getModified
	 */
	public function testModified() {
		$carEntity = new CarEntity(null, null);

		$carEntity->setModified(new \DateTime('2015-05-01 21:42:42'));

		$expected = new \DateTime('2015-05-01 21:42:42');

		$this->assertSame($expected->format('Y-m-d H:i:s'), $carEntity->getModified()->format('Y-m-d H:i:s'));
	}

	/**
	 * @covers ::setCreated
	 * @covers ::getCreated
	 */
	public function testCreated() {
		$carEntity = new CarEntity(null, null);

		$carEntity->setCreated(new \DateTime('2015-05-01 21:42:42'));

		$expected = new \DateTime('2015-05-01 21:42:42');

		$this->assertSame($expected->format('Y-m-d H:i:s'), $carEntity->getCreated()->format('Y-m-d H:i:s'));
	}

}
