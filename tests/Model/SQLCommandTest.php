<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Model;

use Zortje\MVC\Model\SQLCommand;
use Zortje\MVC\Model\Table\Entity\EntityProperty;
use Zortje\MVC\Model\Table\Entity\Exception\InvalidEntityPropertyException;
use Zortje\MVC\Tests\Model\Fixture\CarEntity;
use Zortje\MVC\Tests\Model\Fixture\CarTable;

/**
 * Class SQLCommandTest
 *
 * @package            Zortje\MVC\Tests\Model
 *
 * @coversDefaultClass Zortje\MVC\Model\SQLCommand
 */
class SQLCommandTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var SQLCommand
     */
    private $carsSqlCommand;

    public function setUp()
    {
        $this->pdo = new \PDO("mysql:host=127.0.0.1;dbname=tests", 'root', '');

        /**
         * Table cars; SQLCommand
         */
        $table = new CarTable($this->pdo);

        $this->carsSqlCommand = new SQLCommand($table->getTableName(), CarEntity::getColumns());
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $sqlCommand = new SQLCommand('cars', ['foo', 'bar']);

        $reflector = new \ReflectionClass($sqlCommand);

        $tableName = $reflector->getProperty('tableName');
        $tableName->setAccessible(true);
        $this->assertSame('cars', $tableName->getValue($sqlCommand));

        $columns = $reflector->getProperty('columns');
        $columns->setAccessible(true);
        $this->assertSame(['foo', 'bar'], $columns->getValue($sqlCommand));
    }

    /**
     * @covers ::insertInto
     */
    public function testInsertInto()
    {
        $expected = 'INSERT INTO `cars` (`uuid`, `make`, `model`, `horsepower`, `doors`, `released`, `modified`, `created`) VALUES (:uuid, :make, :model, :horsepower, :doors, :released, :modified, :created);';

        $this->assertSame($expected, $this->carsSqlCommand->insertInto());
    }

    /**
     * @covers ::updateSetWhere
     */
    public function testUpdateSetWhere()
    {
        /**
         * Single column update
         */
        $expected = 'UPDATE `cars` SET `model` = :model WHERE `uuid` = :uuid;';

        $this->assertSame($expected, $this->carsSqlCommand->updateSetWhere(['model']));

        /**
         * Multi column update
         */
        $expected = 'UPDATE `cars` SET `model` = :model, `horsepower` = :horsepower WHERE `uuid` = :uuid;';

        $this->assertSame($expected, $this->carsSqlCommand->updateSetWhere(['model', 'horsepower']));
    }

    /**
     * @covers ::selectFrom
     */
    public function testSelectFrom()
    {
        $expected = 'SELECT `uuid`, `make`, `model`, `horsepower`, `doors`, `released`, `modified`, `created` FROM `cars`;';

        $this->assertSame($expected, $this->carsSqlCommand->selectFrom());
    }

    /**
     * @covers ::selectFromWhere
     */
    public function testSelectFromWhereWithArray()
    {
        /**
         * Single column
         */
        $expected = 'SELECT `uuid`, `make`, `model`, `horsepower`, `doors`, `released`, `modified`, `created` FROM `cars` WHERE `make` = :make AND `model` = :model;';

        $this->assertSame($expected, $this->carsSqlCommand->selectFromWhere(['make', 'model']));

        /**
         * Multiple columns
         */
        $expected = 'SELECT `uuid`, `make`, `model`, `horsepower`, `doors`, `released`, `modified`, `created` FROM `cars` WHERE `make` = :make;';

        $this->assertSame($expected, $this->carsSqlCommand->selectFromWhere(['make']));
    }

    /**
     * @covers ::getColumnNames
     */
    public function testGetColumnNames()
    {
        $reflector = new \ReflectionClass($this->carsSqlCommand);

        $method = $reflector->getMethod('getColumnNames');
        $method->setAccessible(true);

        $this->assertSame('`uuid`, `modified`, `created`', $method->invoke($this->carsSqlCommand, [
            'uuid'     => EntityProperty::UUID,
            'modified' => EntityProperty::STRING,
            'created'  => EntityProperty::STRING
        ]));
    }

    /**
     * @covers ::getColumnValues
     */
    public function testGetColumnValues()
    {
        $reflector = new \ReflectionClass($this->carsSqlCommand);

        $method = $reflector->getMethod('getColumnValues');
        $method->setAccessible(true);

        $this->assertSame(':uuid, :modified, :created', $method->invoke($this->carsSqlCommand, [
            'uuid'     => EntityProperty::UUID,
            'modified' => EntityProperty::STRING,
            'created'  => EntityProperty::STRING
        ]));
    }

    /**
     * @covers ::getEqualFromColumns
     */
    public function testGetEqualFromColumns()
    {
        $reflector = new \ReflectionClass($this->carsSqlCommand);

        $method = $reflector->getMethod('getEqualFromColumns');
        $method->setAccessible(true);

        /**
         * Single column with different glues
         */
        $this->assertSame('`uuid` = :uuid', $method->invoke($this->carsSqlCommand, ', ', ['uuid']));
        $this->assertSame('`uuid` = :uuid', $method->invoke($this->carsSqlCommand, ' AND ', ['uuid']));

        /**
         * Multiple columns with different glues
         */
        $this->assertSame('`make` = :make, `model` = :model', $method->invoke($this->carsSqlCommand, ', ', [
            'make',
            'model'
        ]));
        $this->assertSame('`uuid` = :uuid AND `make` = :make', $method->invoke($this->carsSqlCommand, ' AND ', [
            'uuid',
            'make'
        ]));
    }

    /**
     * @covers ::getEqualFromColumns
     */
    public function testGetEqualFromColumnsInvalid()
    {
        $this->expectException(InvalidEntityPropertyException::class);
        $this->expectExceptionMessage('Entity cars does not have a property named invalid');

        $reflector = new \ReflectionClass($this->carsSqlCommand);

        $method = $reflector->getMethod('getEqualFromColumns');
        $method->setAccessible(true);

        /**
         * Single column with different glues
         */
        $method->invoke($this->carsSqlCommand, '', ['invalid']);
    }
}
