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
			'released' => 'Date',
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
		$car = new CarEntity('Ford', 'Model T', 20, new \DateTime('1908-10-01'));
		$car->set('model', 'Model A');
		$car->set('hp', 65);
		$car->set('released', new \DateTime('1927-10-20'));

		$this->assertSame('Model A', $car->get('model'));
		$this->assertSame(65, $car->get('hp'));
		$this->assertEquals(new \DateTime('1927-10-20'), $car->get('released'));
	}

	/**
	 * @covers ::toArray
	 */
	public function testToArrayWithId() {
		$car = new CarEntity('Ford', 'Model T', 20, new \DateTime('1908-10-01'));

		$now      = new \DateTime();
		$released = new \DateTime('1908-10-01');

		$expected = [
			':id'       => null,
			':make'     => 'Ford',
			':model'    => 'Model T',
			':hp'       => 20,
			':released' => $released->format('Y-m-d'),
			':modified' => $now->format('Y-m-d H:i:s'),
			':created'  => $now->format('Y-m-d H:i:s')
		];

		$this->assertSame($expected, $car->toArray(true));
	}

	/**
	 * @covers ::toArray
	 */
	public function testToArrayWithoutId() {
		$car = new CarEntity('Ford', 'Model T', 20, new \DateTime('1908-10-01'));

		$now      = new \DateTime();
		$released = new \DateTime('1908-10-01');

		$expected = [
			':make'     => 'Ford',
			':model'    => 'Model T',
			':hp'       => 20,
			':released' => $released->format('Y-m-d'),
			':modified' => $now->format('Y-m-d H:i:s'),
			':created'  => $now->format('Y-m-d H:i:s')
		];

		$this->assertSame($expected, $car->toArray(false));
	}

}
