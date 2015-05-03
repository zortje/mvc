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
	public function testToArrayWithId() {
		$car = new CarEntity('Ford', 'Model T', 20);

		$now = new \DateTime();

		$expected = [
			':id'       => null,
			':make'     => 'Ford',
			':model'    => 'Model T',
			':hp'       => 20,
			':modified' => $now->format('Y-m-d H:i:s'),
			':created'  => $now->format('Y-m-d H:i:s')
		];

		$this->assertSame($expected, $car->toArray(true));
	}

	/**
	 * @covers ::toArray
	 */
	public function testToArrayWithoutId() {
		$car = new CarEntity('Ford', 'Model T', 20);

		$now = new \DateTime();

		$expected = [
			':make'     => 'Ford',
			':model'    => 'Model T',
			':hp'       => 20,
			':modified' => $now->format('Y-m-d H:i:s'),
			':created'  => $now->format('Y-m-d H:i:s')
		];

		$this->assertSame($expected, $car->toArray(false));
	}

}
