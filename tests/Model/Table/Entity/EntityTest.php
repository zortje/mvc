<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Model\Table\Entity;

use Ramsey\Uuid\Uuid;
use Zortje\MVC\Model\Table\Entity\Entity;
use Zortje\MVC\Model\Table\Entity\EntityProperty;
use Zortje\MVC\Model\Table\Entity\Exception\InvalidEntityPropertyException;
use Zortje\MVC\Model\Table\Entity\Exception\InvalidValueTypeForEntityPropertyException;
use Zortje\MVC\Tests\Model\Fixture\CarEntity;

/**
 * Class EntityTest
 *
 * @package            Zortje\MVC\Tests\Model\Table\Entity
 *
 * @coversDefaultClass Zortje\MVC\Model\Table\Entity\Entity
 */
class EntityTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     */
    public function testConstructNoId()
    {
        /**
         * @var Entity $entityMock
         */
        $entityMock = $this->getMockBuilder(Entity::class)->disableOriginalConstructor()->getMockForAbstractClass();

        $modified = new \DateTime();
        $created  = new \DateTime();

        $constructor = (new \ReflectionClass(Entity::class))->getConstructor();
        $constructor->invoke($entityMock, null, $modified, $created);

        $this->assertTrue(Uuid::isValid($entityMock->get('id')));
        $this->assertSame($modified, $entityMock->get('modified'));
        $this->assertSame($created, $entityMock->get('created'));
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWithId()
    {
        /**
         * @var Entity $entityMock
         */
        $entityMock = $this->getMockBuilder(Entity::class)->disableOriginalConstructor()->getMockForAbstractClass();

        $id       = Uuid::uuid1()->toString();
        $modified = new \DateTime();
        $created  = new \DateTime();

        $constructor = (new \ReflectionClass(Entity::class))->getConstructor();
        $constructor->invoke($entityMock, $id, $modified, $created);

        $this->assertSame($id, $entityMock->get('id'));
        $this->assertSame($modified, $entityMock->get('modified'));
        $this->assertSame($created, $entityMock->get('created'));
    }

    /**
     * @covers ::getColumns
     */
    public function testGetColumns()
    {
        $expected = [
            'id'         => EntityProperty::UUID,
            'make'       => [
                'type'   => EntityProperty::STRING,
                'length' => 64
            ],
            'model'      => [
                'type'   => EntityProperty::STRING,
                'length' => 64
            ],
            'horsepower' => [
                'type'   => EntityProperty::INTEGER,
                'signed' => false
            ],
            'doors'      => [
                'type'   => EntityProperty::ENUM,
                'values' => [
                    'TWO',
                    'FOUR'
                ]
            ],
            'released'   => EntityProperty::DATE,
            'modified'   => EntityProperty::DATETIME,
            'created'    => EntityProperty::DATETIME
        ];

        $this->assertSame($expected, CarEntity::getColumns());
    }

    /**
     * @covers ::set
     * @covers ::get
     */
    public function testSetAndGet()
    {
        $car = new CarEntity('Ford', 'Model T', 20, 'TWO', new \DateTime('1908-10-01'));
        $car->set('model', 'Model A');
        $car->set('horsepower', 65);
        $car->set('doors', 'FOUR');
        $car->set('released', new \DateTime('1927-10-20'));

        $this->assertSame('Model A', $car->get('model'));
        $this->assertSame(65, $car->get('horsepower'));
        $this->assertSame('FOUR', $car->get('doors'));
        $this->assertEquals(new \DateTime('1927-10-20'), $car->get('released'));
    }

    /**
     * @covers ::set
     */
    public function testSetInvalidProperty()
    {
        $this->expectException(InvalidEntityPropertyException::class);
        $this->expectExceptionMessage('Entity Zortje\MVC\Tests\Model\Fixture\CarEntity does not have a property named invalid-property');

        $car = new CarEntity('', '', 0, 'TWO', new \DateTime());

        $car->set('invalid-property', 'value');
    }

    /**
     * @covers ::get
     */
    public function testGetInvalidProperty()
    {
        $this->expectException(InvalidEntityPropertyException::class);
        $this->expectExceptionMessage('Entity Zortje\MVC\Tests\Model\Fixture\CarEntity does not have a property named invalid-property');

        $car = new CarEntity('', '', 0, 'TWO', new \DateTime());

        $car->get('invalid-property');
    }

    /**
     * @covers ::isAltered
     * @covers ::setUnaltered
     */
    public function testIsAlteredFromConstructor()
    {
        $car = new CarEntity('', '', 0, 'TWO', new \DateTime());

        $this->assertTrue($car->isAltered());

        $expected = [
            'id'         => true,
            'modified'   => true,
            'created'    => true,
            'make'       => true,
            'model'      => true,
            'horsepower' => true,
            'doors'      => true,
            'released'   => true
        ];

        $this->assertSame($expected, $car->getAlteredColumns());

        $car->setUnaltered();

        $this->assertFalse($car->isAltered());
    }

    /**
     * @covers ::isAltered
     * @covers ::getAlteredColumns
     */
    public function testIsAltered()
    {
        $car = new CarEntity('Ford', '', 20, 'TWO', new \DateTime());

        $car->setUnaltered();

        $car->set('horsepower', 21);

        $expected = [
            'horsepower' => true
        ];

        $this->assertSame($expected, $car->getAlteredColumns());

        $car->set('model', 'Volkswagen');

        $expected = [
            'horsepower' => true,
            'model'      => true
        ];

        $this->assertSame($expected, $car->getAlteredColumns());
    }

    /**
     * @covers ::toArray
     */
    public function testToArray()
    {
        $car = new CarEntity('Ford', 'Model T', 20, 'TWO', new \DateTime('1908-10-01'));

        $released = new \DateTime('1908-10-01');

        $expected = [
            ':id'         => $car->get('id'),
            ':make'       => 'Ford',
            ':model'      => 'Model T',
            ':horsepower' => 20,
            ':doors'      => 'TWO',
            ':released'   => $released->format('Y-m-d'),
            ':modified'   => $car->get('modified')->format('Y-m-d H:i:s'),
            ':created'    => $car->get('created')->format('Y-m-d H:i:s')
        ];

        $this->assertSame($expected, $car->toArray());
    }

    /**
     * @covers ::alteredToArray
     */
    public function testAlteredToArrayWithId()
    {
        $car = new CarEntity('Ford', 'Model T', 20, 'TWO', new \DateTime('1908-10-01'));
        $car->setUnaltered();

        $car->set('horsepower', 21);

        $expected = [
            ':id'         => $car->get('id'),
            ':horsepower' => 21
        ];

        $this->assertSame($expected, $car->alteredToArray(true));
    }

    /**
     * @covers ::alteredToArray
     */
    public function testAlteredToArrayWithoutId()
    {
        $car = new CarEntity('Ford', 'Model T', 20, 'TWO', new \DateTime('1908-10-01'));
        $car->setUnaltered();

        $car->set('horsepower', 21);

        $expected = [
            ':horsepower' => 21
        ];

        $this->assertSame($expected, $car->alteredToArray(false));
    }

    /**
     * @covers ::toArrayFromColumns
     */
    public function testToArrayFromColumns()
    {
        $carEntity = new CarEntity('Ford', 'Model T', 20, 'TWO', new \DateTime('1908-10-01'));

        $reflector = new \ReflectionClass($carEntity);

        $method = $reflector->getMethod('toArrayFromColumns');
        $method->setAccessible(true);

        $expected = [
            ':id'         => $carEntity->get('id'),
            ':make'       => 'Ford',
            ':model'      => 'Model T',
            ':horsepower' => 20,
            ':doors'      => 'TWO',
            ':released'   => '1908-10-01',
            ':modified'   => (new \DateTime())->format('Y-m-d H:i:s'),
            ':created'    => (new \DateTime())->format('Y-m-d H:i:s')
        ];

        $this->assertSame($expected, $method->invoke($carEntity, $carEntity::getColumns()));
    }
}
