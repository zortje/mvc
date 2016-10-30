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
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec('SET NAMES utf8');

        /**
         * Table cars; SQLCommand
         */
        $table = new CarTable($this->pdo);

        $this->carsSqlCommand = new SQLCommand($table->getTableName(), CarEntity::getColumns());
    }

    /**
     * @covers ::__construct
     * @covers ::getTableName
     * @covers ::getColumns
     */
    public function testConstruct()
    {
        $sqlCommand = new SQLCommand('cars', ['foo', 'bar']);

        $reflector = new \ReflectionClass($sqlCommand);

        $tableName = $reflector->getProperty('tableName');
        $tableName->setAccessible(true);
        $this->assertSame('cars', $tableName->getValue($sqlCommand));
        $this->assertSame('cars', $sqlCommand->getTableName());

        $columns = $reflector->getProperty('columns');
        $columns->setAccessible(true);
        $this->assertSame(['foo', 'bar'], $columns->getValue($sqlCommand));
        $this->assertSame(['foo', 'bar'], $sqlCommand->getColumns());
    }

    /**
     * @covers ::insertInto
     */
    public function testInsertInto()
    {
        $expected = 'INSERT INTO `cars` (`id`, `make`, `model`, `horsepower`, `doors`, `released`, `modified`, `created`) VALUES (:id, :make, :model, :horsepower, :doors, :released, :modified, :created);';

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
        $expected = 'UPDATE `cars` SET `model` = :model WHERE `id` = :id;';

        $this->assertSame($expected, $this->carsSqlCommand->updateSetWhere(['model']));

        /**
         * Multi column update
         */
        $expected = 'UPDATE `cars` SET `model` = :model, `horsepower` = :horsepower WHERE `id` = :id;';

        $this->assertSame($expected, $this->carsSqlCommand->updateSetWhere(['model', 'horsepower']));
    }

    /**
     * @covers ::selectFrom
     */
    public function testSelectFrom()
    {
        $expected = 'SELECT `id`, `make`, `model`, `horsepower`, `doors`, `released`, `modified`, `created` FROM `cars`;';

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
        $expected = 'SELECT `id`, `make`, `model`, `horsepower`, `doors`, `released`, `modified`, `created` FROM `cars` WHERE `make` = :make AND `model` = :model;';

        $this->assertSame($expected, $this->carsSqlCommand->selectFromWhere(['make', 'model']));

        /**
         * Multiple columns
         */
        $expected = 'SELECT `id`, `make`, `model`, `horsepower`, `doors`, `released`, `modified`, `created` FROM `cars` WHERE `make` = :make;';

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

        $this->assertSame('`id`, `modified`, `created`', $method->invoke($this->carsSqlCommand, [
            'id'       => EntityProperty::UUID,
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

        $this->assertSame(':id, :modified, :created', $method->invoke($this->carsSqlCommand, [
            'id'       => EntityProperty::UUID,
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
        $this->assertSame('`id` = :id', $method->invoke($this->carsSqlCommand, ', ', ['id']));
        $this->assertSame('`id` = :id', $method->invoke($this->carsSqlCommand, ' AND ', ['id']));

        /**
         * Multiple columns with different glues
         */
        $this->assertSame('`make` = :make, `model` = :model', $method->invoke($this->carsSqlCommand, ', ', [
            'make',
            'model'
        ]));
        $this->assertSame('`id` = :id AND `make` = :make', $method->invoke($this->carsSqlCommand, ' AND ', [
            'id',
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
