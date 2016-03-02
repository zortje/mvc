<?php

namespace Zortje\MVC\Tests\Model\Table\Entity;

use Zortje\MVC\Model\Table\Entity\EntityFactory;
use Zortje\MVC\Tests\Model\Fixture\CarEntity;

/**
 * Class EntityFactoryTest
 *
 * @package            Zortje\MVC\Tests\Model\Table\Entity
 *
 * @coversDefaultClass Zortje\MVC\Model\Table\Entity\EntityFactory
 */
class EntityFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $entityFactory = new EntityFactory('Foo');

        $reflector = new \ReflectionClass($entityFactory);

        $property = $reflector->getProperty('entityClass');
        $property->setAccessible(true);
        $this->assertSame('Foo', $property->getValue($entityFactory));
    }

    /**
     * @covers ::createFromArray
     */
    public function testCreateFromArray()
    {
        $entityFactory = new EntityFactory(CarEntity::class);

        /**
         * @var CarEntity $carEntity
         */
        $carEntity = $entityFactory->createFromArray([
            'id'         => '42',
            'make'       => 'Ford',
            'model'      => 'Model T',
            'horsepower' => '20',
            'released'   => '1908-10-01',
            'modified'   => '2015-05-03 00:53:42',
            'created'    => '2015-05-03 00:53:42'
        ]);

        $this->assertFalse($carEntity->isAltered());
        $this->assertSame(CarEntity::class, get_class($carEntity));
        $this->assertSame(42, $carEntity->get('id'));
        $this->assertSame('Ford', $carEntity->get('make'));
        $this->assertSame('Model T', $carEntity->get('model'));
        $this->assertSame(20, $carEntity->get('horsepower'));
        $this->assertEquals(new \DateTime('1908-10-01'), $carEntity->get('released'));
        $this->assertEquals(new \DateTime('2015-05-03 00:53:42'), $carEntity->get('modified'));
        $this->assertEquals(new \DateTime('2015-05-03 00:53:42'), $carEntity->get('created'));
    }
}
