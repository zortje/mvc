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
		$car = new CarEntity('Ford', 'Model T');
		$car->set('model', 'Model A');

		$this->assertSame('Model A', $car->get('model'));
	}

}
