<?php

namespace Zortje\MVC\Tests\Model;

use Zortje\MVC\Model\EntityFactory;
use Zortje\MVC\Tests\Model\Fixture\CarEntity;

/**
 * Class EntityFactoryTest
 *
 * @package            Zortje\MVC\Tests\Model
 *
 * @coversDefaultClass Zortje\MVC\Model\EntityFactory
 */
class EntityFactoryTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::createFromArray
	 */
	public function testCreateFromArray() {
		$entityFactory = new EntityFactory('Zortje\MVC\Tests\Model\Fixture\CarEntity');

		/**
		 * @var CarEntity $carEntity
		 */
		$carEntity = $entityFactory->createFromArray([
			'id'       => '42',
			'make'     => 'Ford',
			'model'    => 'Model T',
			'hp'       => '20',
			'modified' => '2015-05-03 00:53:42',
			'created'  => '2015-05-03 00:53:42'
		]);

		$this->assertSame('Zortje\MVC\Tests\Model\Fixture\CarEntity', get_class($carEntity));
		$this->assertSame(42, $carEntity->get('id'));
		$this->assertSame('Ford', $carEntity->get('make'));
		$this->assertSame('Model T', $carEntity->get('model'));
		$this->assertSame(20, $carEntity->get('hp'));
		$this->assertEquals(new \DateTime('2015-05-03 00:53:42'), $carEntity->get('modified'));
		$this->assertEquals(new \DateTime('2015-05-03 00:53:42'), $carEntity->get('created'));
	}

	/**
	 * @covers ::__construct
	 */
	public function testConstruct() {
		$entityFactory = new EntityFactory('Foo');

		$reflector = new \ReflectionClass($entityFactory);

		$entityClass = $reflector->getProperty('entityClass');
		$entityClass->setAccessible(true);
		$this->assertSame('Foo', $entityClass->getValue($entityFactory));
	}

}
