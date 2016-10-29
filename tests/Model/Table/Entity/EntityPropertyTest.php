<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Model\Table\Entity;

use Zortje\MVC\Model\Table\Entity\EntityProperty;
use Zortje\MVC\Model\Table\Entity\Exception\EntityPropertyTypeNonexistentException;

/**
 * Class EntityPropertyTest
 *
 * @package            Zortje\MVC\Tests\Model\Table\Entity
 *
 * @coversDefaultClass Zortje\MVC\Model\Table\Entity\EntityProperty
 */
class EntityPropertyTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     * @covers ::setType
     *
     * @param string|array $type
     * @param string       $expectedType
     * @param int|null     $expectedLength
     * @param array|null   $expectedValues
     *
     * @dataProvider constructProvider
     */
    public function testConstruct($type, string $expectedType, $expectedLength, $expectedValues)
    {
        $property = new EntityProperty($type);

        $reflector = new \ReflectionClass($property);

        $typeProperty = $reflector->getProperty('type');
        $typeProperty->setAccessible(true);
        $this->assertSame($expectedType, $typeProperty->getValue($property));

        $lengthProperty = $reflector->getProperty('length');
        $lengthProperty->setAccessible(true);
        $this->assertSame($expectedLength, $lengthProperty->getValue($property));

        $valuesProperty = $reflector->getProperty('values');
        $valuesProperty->setAccessible(true);
        $this->assertSame($expectedValues, $valuesProperty->getValue($property));
    }

    /**
     * @return array
     */
    public function constructProvider(): array
    {
        return [
            [EntityProperty::STRING, 'string', null, null],
            [EntityProperty::INTEGER, 'integer', null, null],
            [EntityProperty::FLOAT, 'float', null, null],
            [EntityProperty::DOUBLE, 'double', null, null],
            [EntityProperty::BOOL, 'bool', null, null],
            [EntityProperty::DATE, 'date', null, null],
            [EntityProperty::DATETIME, 'datetime', null, null],
            [EntityProperty::VARBINARY, 'varbinary', null, null],
            [EntityProperty::UUID, 'uuid', null, null],
            [EntityProperty::ENUM, 'enum', null, null],
            [['type' => EntityProperty::STRING, 'length' => 64], 'string', 64, null],
            [['type' => EntityProperty::ENUM, 'values' => ['foo', 'bar']], 'enum', null, ['foo' => true, 'bar' => true]]
        ];
    }

    /**
     * @covers ::__construct
     */
    public function testConstructUndefinedType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Index "type" not found in parameter array');

        new EntityProperty(['length' => 64]);
    }

    /**
     * @covers ::setType
     */
    public function testSetTypeInvalidType()
    {
        $this->expectException(EntityPropertyTypeNonexistentException::class);
        $this->expectExceptionMessage('Entity property type "invalid" is not supported');

        $mock = $this->getMockBuilder(EntityProperty::class)->disableOriginalConstructor()->getMockForAbstractClass();

        $reflector = new \ReflectionClass($mock);

        $method = $reflector->getMethod('setType');
        $method->setAccessible(true);

        $method->invoke($mock, 'invalid');
    }

    //    /** @todo
    //     * @covers ::formatValueForEntity
    //     */
    //    public function testFormatValueStringToString()
    //    {
    //        $property = new EntityProperty(EntityProperty::STRING);
    //
    //        $this->assertSame('foo', $property->formatValueForEntity('foo'));
    //    }

    //    /** @todo
    //     * @covers ::formatValueForEntity
    //     */
    //    public function testFormatValueStringToInteger()
    //    {
    //        $property = new EntityProperty(EntityProperty::INTEGER);
    //
    //        $this->assertSame(42, $property->formatValueForEntity('42'));
    //    }

    //    /** @todo
    //     * @covers ::formatValueForEntity
    //     */
    //    public function testFormatValueStringToFloat()
    //    {
    //        $property = new EntityProperty(EntityProperty::FLOAT);
    //
    //        $this->assertSame(3.14159265359, $property->formatValueForEntity('3.14159265359'));
    //    }

    //    /** @todo
    //     * @covers ::formatValueForEntity
    //     */
    //    public function testFormatValueStringToDateTime()
    //    {
    //        $property = new EntityProperty(EntityProperty::DATETIME);
    //
    //        $this->assertEquals(new \DateTime('2015-05-03 01:15:42'),
    //            $property->formatValueForEntity('2015-05-03 01:15:42'));
    //    }

    //    /** @todo
    //     * @covers ::formatValueForEntity
    //     */
    //    public function testFormatValueStringToDate()
    //    {
    //        $property = new EntityProperty(EntityProperty::DATE);
    //
    //        $this->assertEquals(new \DateTime('2015-05-04'), $property->formatValueForEntity('2015-05-04'));
    //    }

    //    /** @todo
    //     * @covers ::formatValueForDatabase
    //     */
    //    public function testFormatValueForDatabaseDateTime()
    //    {
    //        $property = new EntityProperty(EntityProperty::DATETIME);
    //
    //        $this->assertEquals('2015-05-08 22:42:42',
    //            $property->formatValueForDatabase(new \DateTime('2015-05-08 22:42:42')));
    //    }

    //    /** @todo
    //     * @covers ::formatValueForDatabase
    //     */
    //    public function testFormatValueForDatabaseDate()
    //    {
    //        $property = new EntityProperty(EntityProperty::DATE);
    //
    //        $this->assertEquals('2015-05-08', $property->formatValueForDatabase(new \DateTime('2015-05-08')));
    //    }
}
