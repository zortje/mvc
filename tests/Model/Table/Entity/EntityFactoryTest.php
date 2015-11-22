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
     * @covers ::createFromArray
     */
    public function testCreateFromArray()
    {
        $entityFactory = new EntityFactory(CarEntity::class);

        /**
         * @var CarEntity $carEntity
         */
        $carEntity = $entityFactory->createFromArray([
            'id'       => '42',
            'make'     => 'Ford',
            'model'    => 'Model T',
            'hp'       => '20',
            'released' => '1908-10-01',
            'modified' => '2015-05-03 00:53:42',
            'created'  => '2015-05-03 00:53:42'
        ]);

        $this->assertSame(CarEntity::class, get_class($carEntity));
        $this->assertSame(42, $carEntity->get('id'));
        $this->assertSame('Ford', $carEntity->get('make'));
        $this->assertSame('Model T', $carEntity->get('model'));
        $this->assertSame(20, $carEntity->get('hp'));
        $this->assertEquals(new \DateTime('1908-10-01'), $carEntity->get('released'));
        $this->assertEquals(new \DateTime('2015-05-03 00:53:42'), $carEntity->get('modified'));
        $this->assertEquals(new \DateTime('2015-05-03 00:53:42'), $carEntity->get('created'));
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $entityFactory = new EntityFactory('Foo');

        $reflector = new \ReflectionClass($entityFactory);

        $entityClass = $reflector->getProperty('entityClass');
        $entityClass->setAccessible(true);
        $this->assertSame('Foo', $entityClass->getValue($entityFactory));
    }
}
