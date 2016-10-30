<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Model\Table\Entity;

use Ramsey\Uuid\Uuid;
use Zortje\MVC\Model\Table\Entity\EntityProperty;
use Zortje\MVC\Model\Table\Entity\Exception\EntityPropertyTypeNonexistentException;
use Zortje\MVC\Model\Table\Entity\Exception\EntityPropertyValueExceedingLengthException;
use Zortje\MVC\Model\Table\Entity\Exception\InvalidENUMValueForEntityPropertyException;
use Zortje\MVC\Model\Table\Entity\Exception\InvalidIPAddressValueForEntityPropertyException;
use Zortje\MVC\Model\Table\Entity\Exception\InvalidUUIDValueForEntityPropertyException;
use Zortje\MVC\Model\Table\Entity\Exception\InvalidValueTypeForEntityPropertyException;

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
            [EntityProperty::IPADDRESS, 'ipaddress', null, null],
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


    /**
     * @covers ::validateValue
     */
    public function testValidateValueNull()
    {
        $property = new EntityProperty(EntityProperty::STRING);

        $this->assertTrue($property->validateValue(null));
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueString()
    {
        $property = new EntityProperty(EntityProperty::STRING);

        $this->assertTrue($property->validateValue('foo'));
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueStringInvalid()
    {
        $this->expectException(InvalidValueTypeForEntityPropertyException::class);
        $this->expectExceptionMessage('Entity property expected value type to be "string", got "integer" instead');

        $property = new EntityProperty(EntityProperty::STRING);
        $property->validateValue(42);
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueStringExceedingLength()
    {
        $this->expectException(EntityPropertyValueExceedingLengthException::class);
        $this->expectExceptionMessage('"bar" is longer than 2 characters');

        $property = new EntityProperty(['type' => EntityProperty::STRING, 'length' => 2]);
        $property->validateValue('bar');
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueInteger()
    {
        $property = new EntityProperty(EntityProperty::INTEGER);

        $this->assertTrue($property->validateValue(42));
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueIntegerInvalid()
    {
        $this->expectException(InvalidValueTypeForEntityPropertyException::class);
        $this->expectExceptionMessage('Entity property expected value type to be "integer", got "string" instead');

        $property = new EntityProperty(EntityProperty::INTEGER);
        $property->validateValue('42');
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueFloat()
    {
        $property = new EntityProperty(EntityProperty::FLOAT);

        $this->assertTrue($property->validateValue(3.14));
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueFloatInvalid()
    {
        $this->expectException(InvalidValueTypeForEntityPropertyException::class);
        $this->expectExceptionMessage('Entity property expected value type to be "float", got "string" instead');

        $property = new EntityProperty(EntityProperty::FLOAT);
        $property->validateValue('3.14');
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueDouble()
    {
        $property = new EntityProperty(EntityProperty::DOUBLE);

        $this->assertTrue($property->validateValue(3.14));
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueDoubleInvalid()
    {
        $this->expectException(InvalidValueTypeForEntityPropertyException::class);
        $this->expectExceptionMessage('Entity property expected value type to be "double", got "string" instead');

        $property = new EntityProperty(EntityProperty::DOUBLE);
        $property->validateValue('3.14');
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueBool()
    {
        $property = new EntityProperty(EntityProperty::BOOL);

        $this->assertTrue($property->validateValue(true));
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueBoolInvalid()
    {
        $this->expectException(InvalidValueTypeForEntityPropertyException::class);
        $this->expectExceptionMessage('Entity property expected value type to be "bool", got "string" instead');

        $property = new EntityProperty(EntityProperty::BOOL);
        $property->validateValue('true');
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueDate()
    {
        $property = new EntityProperty(EntityProperty::DATE);

        $this->assertTrue($property->validateValue(new \DateTime()));
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueDateInvalid()
    {
        $this->expectException(InvalidValueTypeForEntityPropertyException::class);
        $this->expectExceptionMessage('Entity property expected value type to be "date", got "string" instead');

        $property = new EntityProperty(EntityProperty::DATE);
        $property->validateValue('2016-10-30');
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueDateTime()
    {
        $property = new EntityProperty(EntityProperty::DATETIME);

        $this->assertTrue($property->validateValue(new \DateTime()));
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueDateTimeInvalid()
    {
        $this->expectException(InvalidValueTypeForEntityPropertyException::class);
        $this->expectExceptionMessage('Entity property expected value type to be "datetime", got "string" instead');

        $property = new EntityProperty(EntityProperty::DATETIME);
        $property->validateValue('2016-10-30 22:11:42');
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueIPAddress()
    {
        $property = new EntityProperty(EntityProperty::IPADDRESS);

        $this->assertTrue($property->validateValue('192.0.2.1'));
        $this->assertTrue($property->validateValue('FF01:0:0:0:0:0:0:FB'));
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueIPAddressInvalidIPAddress()
    {
        $this->expectException(InvalidIPAddressValueForEntityPropertyException::class);
        $this->expectExceptionMessage('"42" is not a valid IP address');

        $property = new EntityProperty(EntityProperty::IPADDRESS);
        $property->validateValue('42');
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueUUID()
    {
        $property = new EntityProperty(EntityProperty::UUID);

        $this->assertTrue($property->validateValue(Uuid::uuid1()->toString()));
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueUUIDInvalid()
    {
        $this->expectException(InvalidValueTypeForEntityPropertyException::class);
        $this->expectExceptionMessage('Entity property expected value type to be "string", got "integer" instead');

        $property = new EntityProperty(EntityProperty::UUID);
        $property->validateValue(42);
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueUUIDInvalidUUID()
    {
        $this->expectException(InvalidUUIDValueForEntityPropertyException::class);
        $this->expectExceptionMessage('"xx-x-x-xxx" is not a valid UUID value');

        $property = new EntityProperty(EntityProperty::UUID);
        $property->validateValue('xx-x-x-xxx');
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueEnum()
    {
        $property = new EntityProperty([
            'type'   => EntityProperty::ENUM,
            'values' => [
                'foo',
                'bar'
            ]
        ]);

        $this->assertTrue($property->validateValue('foo'));
        $this->assertTrue($property->validateValue('bar'));
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueEnumInvalid()
    {
        $this->expectException(InvalidValueTypeForEntityPropertyException::class);
        $this->expectExceptionMessage('Entity property expected value type to be "string", got "integer" instead');

        $property = new EntityProperty(EntityProperty::ENUM);
        $property->validateValue(42);
    }

    /**
     * @covers ::validateValue
     */
    public function testValidateValueEnumInvalidValue()
    {
        $this->expectException(InvalidENUMValueForEntityPropertyException::class);
        $this->expectExceptionMessage('"bar" is not a valid ENUM value');

        $property = new EntityProperty([
            'type'   => EntityProperty::ENUM,
            'values' => [
                'foo'
            ]
        ]);

        $property->validateValue('bar');
    }

    /**
     * @covers ::formatValueForEntity
     */
    public function testFormatValueNull()
    {
        $property = new EntityProperty(EntityProperty::STRING);

        $this->assertSame(null, $property->formatValueForEntity(null));
    }

    /**
     * @covers ::formatValueForEntity
     */
    public function testFormatValueStringToString()
    {
        $property = new EntityProperty(EntityProperty::STRING);

        $this->assertSame('foo', $property->formatValueForEntity('foo'));
    }

    /**
     * @covers ::formatValueForEntity
     */
    public function testFormatValueStringToInteger()
    {
        $property = new EntityProperty(EntityProperty::INTEGER);

        $this->assertSame(42, $property->formatValueForEntity('42'));
    }

    /**
     * @covers ::formatValueForEntity
     */
    public function testFormatValueStringToFloat()
    {
        $property = new EntityProperty(EntityProperty::FLOAT);

        $this->assertSame(3.14159265359, $property->formatValueForEntity('3.14159265359'));
    }

    /**
     * @covers ::formatValueForEntity
     */
    public function testFormatValueStringToDateTime()
    {
        $property = new EntityProperty(EntityProperty::DATETIME);

        $this->assertEquals(
            new \DateTime('2015-05-03 01:15:42'),
            $property->formatValueForEntity('2015-05-03 01:15:42')
        );
    }

    /**
     * @covers ::formatValueForEntity
     */
    public function testFormatValueStringToDate()
    {
        $property = new EntityProperty(EntityProperty::DATE);

        $this->assertEquals(new \DateTime('2015-05-04'), $property->formatValueForEntity('2015-05-04'));
    }

    /**
     * @covers ::formatValueForEntity
     */
    public function testFormatValueVarbinaryToIP()
    {
        $property = new EntityProperty(EntityProperty::IPADDRESS);

        $this->assertEquals('192.0.2.1', $property->formatValueForEntity(inet_pton('192.0.2.1')));
        $this->assertEquals('ff01::fb', $property->formatValueForEntity(inet_pton('ff01::fb')));
    }

    /**
     * @covers ::formatValueForEntity
     */
    public function testFormatValueStringToBool()
    {
        $property = new EntityProperty(EntityProperty::BOOL);

        $this->assertSame(false, $property->formatValueForEntity('0'));
        $this->assertSame(true, $property->formatValueForEntity('1'));
    }

    /**
     * @covers ::formatValueForDatabase
     */
    public function testFormatValueForDatabaseDateTime()
    {
        $property = new EntityProperty(EntityProperty::DATETIME);

        $this->assertEquals(
            '2015-05-08 22:42:42',
            $property->formatValueForDatabase(new \DateTime('2015-05-08 22:42:42'))
        );
    }

    /**
     * @covers ::formatValueForDatabase
     */
    public function testFormatValueForDatabaseDate()
    {
        $property = new EntityProperty(EntityProperty::DATE);

        $this->assertEquals('2015-05-08', $property->formatValueForDatabase(new \DateTime('2015-05-08')));
    }

    /**
     * @covers ::formatValueForDatabase
     */
    public function testFormatValueForDatabaseBool()
    {
        $property = new EntityProperty(EntityProperty::BOOL);

        $this->assertEquals('0', $property->formatValueForDatabase(false));
        $this->assertEquals('1', $property->formatValueForDatabase(true));
    }

    /**
     * @covers ::formatValueForDatabase
     */
    public function testFormatValueForDatabaseIPAddress()
    {
        $property = new EntityProperty(EntityProperty::IPADDRESS);

        $this->assertEquals(inet_pton('192.0.2.1'), $property->formatValueForDatabase('192.0.2.1'));
        $this->assertEquals(inet_pton('ff01::fb'), $property->formatValueForDatabase('ff01::fb'));
    }
}
