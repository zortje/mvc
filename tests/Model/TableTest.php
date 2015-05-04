<?php

namespace Zortje\MVC\Tests\Model;

use Zortje\MVC\Tests\Model\Fixture\CarEntity;
use Zortje\MVC\Tests\Model\Fixture\CarTable;

/**
 * Class TableTest
 *
 * @package            Zortje\MVC\Tests\Model
 *
 * @coversDefaultClass Zortje\MVC\Model\Table
 */
class TableTest extends \PHPUnit_Extensions_Database_TestCase {

	private $pdo;

	/**
	 * Returns the test database connection.
	 *
	 * @return \PHPUnit_Extensions_Database_DB_IDatabaseConnection
	 */
	protected function getConnection() {
		$this->pdo = new \PDO("mysql:host=127.0.0.1;dbname=myapp_test", 'root', '');

		return $this->createDefaultDBConnection($this->pdo, 'test');
	}

	/**
	 * Returns the test dataset.
	 *
	 * @return \PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	protected function getDataSet() {
		$dataSet = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet();

		$dataSet->addTable('cars', dirname(__FILE__) . "/Fixture/cars.csv");

		return $dataSet;
	}

	/**
	 * @covers ::__construct
	 */
	public function testConstruct() {
		$carTable = new CarTable($this->pdo);

		$reflector = new \ReflectionClass($carTable);

		$tableName = $reflector->getProperty('tableName');
		$tableName->setAccessible(true);
		$this->assertSame('cars', $tableName->getValue($carTable));

		$entityClass = $reflector->getProperty('entityClass');
		$entityClass->setAccessible(true);
		$this->assertSame('Zortje\MVC\Tests\Model\Fixture\CarEntity', $entityClass->getValue($carTable));

		// @todo Test if SQL Command object is created correct
	}

	/**
	 * @covers ::getTableName
	 */
	public function testGetTableName() {
		$carTable = new CarTable($this->pdo);

		$this->assertSame('cars', $carTable->getTableName());
	}

	/**
	 * @covers ::findAll
	 */
	public function testFindAll() {
		$carTable = new CarTable($this->pdo);

		$cars = $carTable->findAll();

		$this->assertSame(2, count($cars));

		/**
		 * First entity
		 */
		$car = $cars[0];

		$this->assertSame('Zortje\MVC\Tests\Model\Fixture\CarEntity', get_class($car));
		$this->assertSame(1, $car->get('id'));
		$this->assertSame('Ford', $car->get('make'));
		$this->assertSame('Model T', $car->get('model'));
		$this->assertSame(20, $car->get('hp'));
		$this->assertEquals(new \DateTime('1908-10-01'), $car->get('released'));
		$this->assertEquals(new \DateTime('2015-05-03 21:18:42'), $car->get('modified'));
		$this->assertEquals(new \DateTime('2015-05-03 21:18:42'), $car->get('created'));

		/**
		 * Second entity
		 */
		$car = $cars[1];

		$this->assertSame('Zortje\MVC\Tests\Model\Fixture\CarEntity', get_class($car));
		$this->assertSame(2, $car->get('id'));
		$this->assertSame('Ford', $car->get('make'));
		$this->assertSame('Model A', $car->get('model'));
		$this->assertSame(40, $car->get('hp'));
		$this->assertEquals(new \DateTime('1927-10-20'), $car->get('released'));
		$this->assertEquals(new \DateTime('2015-05-03 21:18:42'), $car->get('modified'));
		$this->assertEquals(new \DateTime('2015-05-03 21:18:42'), $car->get('created'));
	}

	/**
	 * @covers ::findBy
	 */
	public function testFindBy() {
		$carTable = new CarTable($this->pdo);

		$cars = $carTable->findBy('hp', 20);

		$this->assertSame(1, count($cars));

		/**
		 * First entity
		 */
		$car = $cars[0];

		$this->assertSame('Zortje\MVC\Tests\Model\Fixture\CarEntity', get_class($car));
		$this->assertSame(1, $car->get('id'));
		$this->assertSame('Ford', $car->get('make'));
		$this->assertSame('Model T', $car->get('model'));
		$this->assertSame(20, $car->get('hp'));
		$this->assertEquals(new \DateTime('1908-10-01'), $car->get('released'));
		$this->assertEquals(new \DateTime('2015-05-03 21:18:42'), $car->get('modified'));
		$this->assertEquals(new \DateTime('2015-05-03 21:18:42'), $car->get('created'));
	}

	/**
	 * @covers ::insert
	 */
	public function testInsert() {
		$carTable = new CarTable($this->pdo);

		$car = new CarEntity('Ford', 'Model B', 65, new \DateTime('1932-01-01'));

		$id = $carTable->insert($car);

		$this->assertSame(3, $id);

		/**
		 * Assert data set
		 */
		$expectedDataSet = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet();
		$expectedDataSet->addTable('cars', dirname(__FILE__) . "/Fixture/cars_after-insertion.csv");

		$expectedDataSet = new \PHPUnit_Extensions_Database_DataSet_DataSetFilter($expectedDataSet);
		$expectedDataSet->setExcludeColumnsForTable('cars', ['modified', 'created']);

		$dataSet = new \PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
		$dataSet->addTable('cars', 'SELECT * FROM `cars`');

		$dataSet = new \PHPUnit_Extensions_Database_DataSet_DataSetFilter($dataSet);
		$dataSet->setExcludeColumnsForTable('cars', ['modified', 'created']);

		$this->assertDataSetsEqual($expectedDataSet, $dataSet);
	}

}
