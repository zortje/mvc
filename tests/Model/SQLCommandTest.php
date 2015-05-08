<?php

namespace Zortje\MVC\Tests\Model;

use Zortje\MVC\Model\SQLCommand;
use Zortje\MVC\Tests\Model\Fixture\CarEntity;
use Zortje\MVC\Tests\Model\Fixture\CarTable;

/**
 * Class SQLCommandTest
 *
 * @package            Zortje\MVC\Tests\Model
 *
 * @coversDefaultClass Zortje\MVC\Model\SQLCommand
 */
class SQLCommandTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \PDO
	 */
	private $pdo;

	/**
	 * @var SQLCommand
	 */
	private $carsSqlCommand;

	public function setUp() {
		$this->pdo = new \PDO("mysql:host=127.0.0.1;dbname=myapp_test", 'root', '');

		/**
		 * Table cars; SQLCommand
		 */
		$table = new CarTable($this->pdo);

		$this->carsSqlCommand = new SQLCommand($table->getTableName(), CarEntity::getColumns());
	}

	/**
	 * @covers ::insertInto
	 */
	public function testInsertInto() {
		$expected = 'INSERT INTO `cars` (`id`, `make`, `model`, `hp`, `released`, `modified`, `created`) VALUES (NULL, :make, :model, :hp, :released, :modified, :created);';

		$this->assertSame($expected, $this->carsSqlCommand->insertInto());
	}

	/**
	 * @covers ::selectFrom
	 */
	public function testSelectFrom() {
		$expected = 'SELECT `id`, `make`, `model`, `hp`, `released`, `modified`, `created` FROM `cars`;';

		$this->assertSame($expected, $this->carsSqlCommand->selectFrom());
	}

	/**
	 * @covers ::selectFromWhere
	 */
	public function testSelectFromWhereWithArray() {
		$expected = 'SELECT `id`, `make`, `model`, `hp`, `released`, `modified`, `created` FROM `cars` WHERE `make` = :make AND `model` = :model;';

		$this->assertSame($expected, $this->carsSqlCommand->selectFromWhere(['make', 'model']));
	}

	/**
	 * @covers ::selectFromWhere
	 */
	public function testSelectFromWhereWithString() {
		$expected = 'SELECT `id`, `make`, `model`, `hp`, `released`, `modified`, `created` FROM `cars` WHERE `make` = :make;';

		$this->assertSame($expected, $this->carsSqlCommand->selectFromWhere('make'));
	}

	/**
	 * @covers ::getColumnNames
	 */
	public function testGetColumnNames() {
		$reflector = new \ReflectionClass($this->carsSqlCommand);

		$method = $reflector->getMethod('getColumnNames');
		$method->setAccessible(true);

		$this->assertSame('`id`, `modified`, `created`', $method->invoke($this->carsSqlCommand, [
			'id'       => 'integer',
			'modified' => 'string',
			'created'  => 'string'
		]));
	}

	/**
	 * @covers ::getColumnValues
	 */
	public function testGetColumnValues() {
		$reflector = new \ReflectionClass($this->carsSqlCommand);

		$method = $reflector->getMethod('getColumnValues');
		$method->setAccessible(true);

		$this->assertSame(':id, :modified, :created', $method->invoke($this->carsSqlCommand, [
			'id'       => 'integer',
			'modified' => 'string',
			'created'  => 'string'
		]));
	}

	/**
	 * @covers ::getWhereConditionFromKeys
	 */
	public function testGetWhereConditionFromKeys() {
		$reflector = new \ReflectionClass($this->carsSqlCommand);

		$method = $reflector->getMethod('getWhereConditionFromKeys');
		$method->setAccessible(true);

		$this->assertSame('`id` = :id', $method->invoke($this->carsSqlCommand, 'id'));
		$this->assertSame('`id` = :id AND `active` = :active', $method->invoke($this->carsSqlCommand, [
			'id',
			'active'
		]));
	}

	/**
	 * @covers ::getWhereConditionFromKeys
	 *
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Keys must be a string or an array of strings
	 */
	public function testGetWhereConditionFromKeysInvalidArgument() {
		$reflector = new \ReflectionClass($this->carsSqlCommand);

		$method = $reflector->getMethod('getWhereConditionFromKeys');
		$method->setAccessible(true);

		$method->invoke($this->carsSqlCommand, 42);
	}

	/**
	 * @covers ::__construct
	 */
	public function testConstruct() {
		$sqlCommand = new SQLCommand('cars', ['foo', 'bar']);

		$reflector = new \ReflectionClass($sqlCommand);

		$tableName = $reflector->getProperty('tableName');
		$tableName->setAccessible(true);
		$this->assertSame('cars', $tableName->getValue($sqlCommand));

		$columns = $reflector->getProperty('columns');
		$columns->setAccessible(true);
		$this->assertSame(['foo', 'bar'], $columns->getValue($sqlCommand));
	}

}
