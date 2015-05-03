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
	 * @covers ::getColumns
	 */
	public function testGetColumns() {
		$expected = [
			'id'       => 'integer',
			'make'     => 'string',
			'model'    => 'string',
			'hp'       => 'integer',
			'modified' => 'DateTime',
			'created'  => 'DateTime'
		];

		$this->assertSame($expected, CarEntity::getColumns());
	}

	/**
	 * @covers ::set
	 * @covers ::get
	 */
	public function testSetAndGet() {
		$car = new CarEntity('Ford', 'Model T', 20);
		$car->set('model', 'Model A');
		$car->set('hp', 65);

		$this->assertSame('Model A', $car->get('model'));
		$this->assertSame(65, $car->get('hp'));
	}

	/**
	 * @covers ::toArray
	 */
	public function testToArray() {
		$car = new CarEntity('Ford', 'Model T', 20);

		$expected = [
			'id'       => null,
			'make'     => 'Ford',
			'model'    => 'Model T',
			'hp'       => 20,
			'modified' => new \DateTime(),
			'created'  => new \DateTime()
		];

		$toArray = $car->toArray();

		$this->assertEquals($expected, $toArray);

		$this->assertEquals(null, $toArray['id']);
		$this->assertEquals('Ford', $toArray['make']);
		$this->assertEquals('Model T', $toArray['model']);
		$this->assertEquals(20, $toArray['hp']);
	}
}
