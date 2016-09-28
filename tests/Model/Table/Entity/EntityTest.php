<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Model\Table\Entity;

use Ramsey\Uuid\Uuid;
use Zortje\MVC\Model\Table\Entity\Entity;
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
    public function testConstructNoUuid()
    {
        /**
         * @var Entity $entityMock
         */
        $entityMock = $this->getMockBuilder(Entity::class)->disableOriginalConstructor()->getMockForAbstractClass();

        $modified = new \DateTime();
        $created  = new \DateTime();

        $constructor = (new \ReflectionClass(Entity::class))->getConstructor();
        $constructor->invoke($entityMock, null, $modified, $created);

        $this->assertTrue(Uuid::isValid($entityMock->get('uuid')));
        $this->assertSame($modified, $entityMock->get('modified'));
        $this->assertSame($created, $entityMock->get('created'));
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWithUuid()
    {
        /**
         * @var Entity $entityMock
         */
        $entityMock = $this->getMockBuilder(Entity::class)->disableOriginalConstructor()->getMockForAbstractClass();

        $uuid     = Uuid::uuid1()->toString();
        $modified = new \DateTime();
        $created  = new \DateTime();

        $constructor = (new \ReflectionClass(Entity::class))->getConstructor();
        $constructor->invoke($entityMock, $uuid, $modified, $created);

        $this->assertSame($uuid, $entityMock->get('uuid'));
        $this->assertSame($modified, $entityMock->get('modified'));
        $this->assertSame($created, $entityMock->get('created'));
    }

    /**
     * @covers ::getColumns
     */
    public function testGetColumns()
    {
        $expected = [
            'uuid'       => 'uuid',
            'make'       => 'string',
            'model'      => 'string',
            'horsepower' => 'integer',
            'released'   => 'date',
            'modified'   => 'datetime',
            'created'    => 'datetime'
        ];

        $this->assertSame($expected, CarEntity::getColumns());
    }

    /**
     * @covers ::set
     * @covers ::get
     */
    public function testSetAndGet()
    {
        $car = new CarEntity('Ford', 'Model T', 20, new \DateTime('1908-10-01'));
        $car->set('model', 'Model A');
        $car->set('horsepower', 65);
        $car->set('released', new \DateTime('1927-10-20'));

        $this->assertSame('Model A', $car->get('model'));
        $this->assertSame(65, $car->get('horsepower'));
        $this->assertEquals(new \DateTime('1927-10-20'), $car->get('released'));
    }

    /**
     * @covers ::set
     */
    public function testSetInvalidProperty()
    {
        $this->expectException(InvalidEntityPropertyException::class);
        $this->expectExceptionMessage('Entity Zortje\MVC\Tests\Model\Fixture\CarEntity does not have a property named invalid-property');

        $car = new CarEntity('', '', 0, new \DateTime());

        $car->set('invalid-property', 'value');
    }

    /**
     * @covers ::get
     */
    public function testGetInvalidProperty()
    {
        $this->expectException(InvalidEntityPropertyException::class);
        $this->expectExceptionMessage('Entity Zortje\MVC\Tests\Model\Fixture\CarEntity does not have a property named invalid-property');

        $car = new CarEntity('', '', 0, new \DateTime());

        $car->get('invalid-property');
    }

    /**
     * @covers ::validatePropertyValueType
     */
    public function testValidatePropertyValueType()
    {
        $car = new CarEntity('', '', 0, new \DateTime());

        $reflector = new \ReflectionClass($car);

        $method = $reflector->getMethod('validatePropertyValueType');
        $method->setAccessible(true);

        /**
         * Entity
         */
        $this->assertSame(null, $method->invoke($car, 'uuid', null), 'UUID property');
        $this->assertSame('f2a88758-8251-11e6-ae22-56b6b6499611',
            $method->invoke($car, 'uuid', 'f2a88758-8251-11e6-ae22-56b6b6499611'), 'UUID property');

        $this->assertEquals(new \DateTime(), $method->invoke($car, 'modified', new \DateTime()), 'Modified property');
        $this->assertEquals(new \DateTime(), $method->invoke($car, 'created', new \DateTime()), 'Created property');

        /**
         * CarEntity
         */
        $this->assertSame(null, $method->invoke($car, 'make', null), 'Make property');
        $this->assertSame('Ford', $method->invoke($car, 'make', 'Ford'), 'Make property');

        $this->assertSame(null, $method->invoke($car, 'model', null), 'Model property');
        $this->assertSame('Model A', $method->invoke($car, 'model', 'Model A'), 'Model property');

        $this->assertSame(null, $method->invoke($car, 'horsepower', null), 'Horsepower property');
        $this->assertSame(65, $method->invoke($car, 'horsepower', 65), 'Horsepower property');

        $this->assertEquals(null, $method->invoke($car, 'released', null), 'Released  property');
        $this->assertEquals(new \DateTime('1927-10-20'), $method->invoke($car, 'released', new \DateTime('1927-10-20')),
            'Released  property');
    }

    /**
     * @covers ::validatePropertyValueType
     */
    public function testValidatePropertyValueTypeInvaidProperty()
    {
        $message = 'Entity Zortje\MVC\Tests\Model\Fixture\CarEntity does not have a property named invalid-property';

        $this->expectException(InvalidEntityPropertyException::class);
        $this->expectExceptionMessage($message);

        $car = new CarEntity('', '', 0, new \DateTime());

        $reflector = new \ReflectionClass($car);

        $method = $reflector->getMethod('validatePropertyValueType');
        $method->setAccessible(true);

        $method->invoke($car, 'invalid-property', 'value');
    }

    /**
     * @covers ::validatePropertyValueType
     */
    public function testValidatePropertyValueTypeInvalidValue()
    {
        $message = 'Entity "Zortje\MVC\Tests\Model\Fixture\CarEntity" property "uuid" is of type "string" and not expected type "uuid"';

        $this->expectException(InvalidValueTypeForEntityPropertyException::class);
        $this->expectExceptionMessage($message);

        $car = new CarEntity('', '', 0, new \DateTime());

        $reflector = new \ReflectionClass($car);

        $method = $reflector->getMethod('validatePropertyValueType');
        $method->setAccessible(true);

        $method->invoke($car, 'uuid', 'string');
    }

    /**
     * @covers ::isAltered
     * @covers ::setUnaltered
     */
    public function testIsAlteredFromConstructor()
    {
        $car = new CarEntity('', '', 0, new \DateTime());

        $this->assertTrue($car->isAltered());

        $expected = [
            'uuid'       => true,
            'modified'   => true,
            'created'    => true,
            'make'       => true,
            'model'      => true,
            'horsepower' => true,
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
        $car = new CarEntity('Ford', '', 20, new \DateTime());

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
        $car = new CarEntity('Ford', 'Model T', 20, new \DateTime('1908-10-01'));

        $released = new \DateTime('1908-10-01');

        $expected = [
            ':uuid'       => $car->get('uuid'),
            ':make'       => 'Ford',
            ':model'      => 'Model T',
            ':horsepower' => 20,
            ':released'   => $released->format('Y-m-d'),
            ':modified'   => $car->get('modified')->format('Y-m-d H:i:s'),
            ':created'    => $car->get('created')->format('Y-m-d H:i:s')
        ];

        $this->assertSame($expected, $car->toArray());
    }

    /**
     * @covers ::alteredToArray
     */
    public function testAlteredToArrayWithUuid()
    {
        $car = new CarEntity('Ford', 'Model T', 20, new \DateTime('1908-10-01'));
        $car->setUnaltered();

        $car->set('horsepower', 21);

        $expected = [
            ':uuid'       => $car->get('uuid'),
            ':horsepower' => 21
        ];

        $this->assertSame($expected, $car->alteredToArray(true));
    }

    /**
     * @covers ::alteredToArray
     */
    public function testAlteredToArrayWithoutUuid()
    {
        $car = new CarEntity('Ford', 'Model T', 20, new \DateTime('1908-10-01'));
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
        $this->markTestIncomplete(); // @todo
    }
}
