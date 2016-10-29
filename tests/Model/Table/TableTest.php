<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Model\Table;

use Zortje\MVC\Model\Table\Entity\Exception\InvalidEntityPropertyException;
use Zortje\MVC\Model\Table\Entity\Exception\InvalidValueTypeForEntityPropertyException;
use Zortje\MVC\Tests\Model\Fixture\CarEntity;
use Zortje\MVC\Tests\Model\Fixture\CarTable;

/**
 * Class TableTest
 *
 * @package            Zortje\MVC\Tests\Model\Table
 *
 * @coversDefaultClass Zortje\MVC\Model\Table\Table
 */
class TableTest extends \PHPUnit_Extensions_Database_TestCase
{

    private $pdo;

    /**
     * Returns the test database connection.
     *
     * @return \PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    protected function getConnection()
    {
        $this->pdo = new \PDO('mysql:host=127.0.0.1;dbname=tests', 'root', '');

        return $this->createDefaultDBConnection($this->pdo, 'test');
    }

    /**
     * Returns the test dataset.
     *
     * @return \PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        $dataSet = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet();

        $dataSet->addTable('cars', dirname(__FILE__) . '/../Fixture/cars.csv');

        return $dataSet;
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $carTable = new CarTable($this->pdo);

        $reflector = new \ReflectionClass($carTable);

        $tableName = $reflector->getProperty('tableName');
        $tableName->setAccessible(true);
        $this->assertSame('cars', $tableName->getValue($carTable));

        $entityClass = $reflector->getProperty('entityClass');
        $entityClass->setAccessible(true);
        $this->assertSame(CarEntity::class, $entityClass->getValue($carTable));
    }

    /**
     * @covers ::getTableName
     */
    public function testGetTableName()
    {
        $carTable = new CarTable($this->pdo);

        $this->assertSame('cars', $carTable->getTableName());
    }

    /**
     * @covers ::findAll
     */
    public function testFindAll()
    {
        $carTable = new CarTable($this->pdo);

        $cars = $carTable->findAll();

        $this->assertCount(2, $cars);

        /**
         * First entity
         */
        $car = $cars[0];

        $this->assertSame(CarEntity::class, get_class($car));
        $this->assertSame('634d28b6-8251-11e6-ae22-56b6b6499611', $car->get('uuid'));
        $this->assertSame('Ford', $car->get('make'));
        $this->assertSame('Model T', $car->get('model'));
        $this->assertSame(20, $car->get('horsepower'));
        $this->assertEquals(new \DateTime('1908-10-01'), $car->get('released'));
        $this->assertEquals(new \DateTime('2015-05-03 21:18:42'), $car->get('modified'));
        $this->assertEquals(new \DateTime('2015-05-03 21:18:42'), $car->get('created'));

        /**
         * Second entity
         */
        $car = $cars[1];

        $this->assertSame(CarEntity::class, get_class($car));
        $this->assertSame('9b6942f2-8251-11e6-ae22-56b6b6499611', $car->get('uuid'));
        $this->assertSame('Ford', $car->get('make'));
        $this->assertSame('Model A', $car->get('model'));
        $this->assertSame(40, $car->get('horsepower'));
        $this->assertEquals(new \DateTime('1927-10-20'), $car->get('released'));
        $this->assertEquals(new \DateTime('2015-05-03 21:18:42'), $car->get('modified'));
        $this->assertEquals(new \DateTime('2015-05-03 21:18:42'), $car->get('created'));
    }

    /**
     * @covers ::findBy
     */
    public function testFindBy()
    {
        $carTable = new CarTable($this->pdo);

        $cars = $carTable->findBy('horsepower', 20);

        $this->assertCount(1, $cars);

        /**
         * First entity
         */
        $car = $cars[0];

        $this->assertSame(CarEntity::class, get_class($car));
        $this->assertSame('634d28b6-8251-11e6-ae22-56b6b6499611', $car->get('uuid'));
        $this->assertSame('Ford', $car->get('make'));
        $this->assertSame('Model T', $car->get('model'));
        $this->assertSame(20, $car->get('horsepower'));
        $this->assertEquals(new \DateTime('1908-10-01'), $car->get('released'));
        $this->assertEquals(new \DateTime('2015-05-03 21:18:42'), $car->get('modified'));
        $this->assertEquals(new \DateTime('2015-05-03 21:18:42'), $car->get('created'));
    }

    /**
     * @covers ::findBy
     */
    public function testFindByEmpty()
    {
        $carTable = new CarTable($this->pdo);

        $cars = $carTable->findBy('horsepower', 1337);

        $this->assertCount(0, $cars);
    }

    /**
     * @covers ::findBy
     */
    public function testFindByInvalid()
    {
        $message = 'Entity Zortje\MVC\Tests\Model\Fixture\CarEntity does not have a property named invalid-property';

        $this->expectException(InvalidEntityPropertyException::class);
        $this->expectExceptionMessage($message);

        $carTable = new CarTable($this->pdo);

        $carTable->findBy('invalid-property', 20);
    }

    /**
     * @covers ::findBy
     */
    public function testFindByException()
    {
        $message = 'Entity property expected value type to be "string", got "integer" instead';

        $this->expectException(InvalidValueTypeForEntityPropertyException::class);
        $this->expectExceptionMessage($message);

        $carTable = new CarTable($this->pdo);

        $cars = $carTable->findBy('horsepower', '1337');

        $this->assertCount(0, $cars);
    }

    /**
     * @covers ::insert
     */
    public function testInsert()
    {
        $carTable = new CarTable($this->pdo);

        $car = new CarEntity('Ford', 'Model B', 65, 'TWO', new \DateTime('1932-01-01'));

        $insertedCar = $carTable->insert($car);

        $this->assertSame($car, $insertedCar);

        /**
         * Assert data set
         */
        $expectedCsvDataSet = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $expectedCsvDataSet->addTable('cars', dirname(__FILE__) . '/../Fixture/cars_after-insertion.csv');

        $expectedDataSet = new \PHPUnit_Extensions_Database_DataSet_DataSetFilter($expectedCsvDataSet);
        $expectedDataSet->setExcludeColumnsForTable('cars', ['uuid', 'modified', 'created']);

        $dataSet = new \PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
        $dataSet->addTable('cars', 'SELECT * FROM `cars` ORDER BY `released` ASC');

        $dataSet = new \PHPUnit_Extensions_Database_DataSet_DataSetFilter($dataSet);
        $dataSet->setExcludeColumnsForTable('cars', ['uuid', 'modified', 'created']);

        $this->assertDataSetsEqual($expectedDataSet, $dataSet);
    }

    /**
     * @covers ::update
     */
    public function testUpdate()
    {

        $carTable = new CarTable($this->pdo);

        $cars = $carTable->findAll();

        $this->assertCount(2, $cars);

        /**
         * Alter first car
         */
        $firstCar = $cars[0];

        $this->assertFalse($firstCar->isAltered());

        $firstCar->set('horsepower', 21);

        $this->assertTrue($firstCar->isAltered());

        $carTable->update($firstCar);

        /**
         * Alter second car
         */
        $secondCar = $cars[1];

        $this->assertFalse($secondCar->isAltered());

        $secondCar->set('horsepower', 41);

        $this->assertTrue($secondCar->isAltered());

        $carTable->update($secondCar);

        /**
         * Assert data set
         */
        $expectedCsvDataSet = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $expectedCsvDataSet->addTable('cars', dirname(__FILE__) . '/../Fixture/cars_after-update.csv');

        $expectedDataSet = new \PHPUnit_Extensions_Database_DataSet_DataSetFilter($expectedCsvDataSet);
        $expectedDataSet->setExcludeColumnsForTable('cars', ['modified', 'created']);

        $dataSet = new \PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
        $dataSet->addTable('cars', 'SELECT * FROM `cars`');

        $dataSet = new \PHPUnit_Extensions_Database_DataSet_DataSetFilter($dataSet);
        $dataSet->setExcludeColumnsForTable('cars', ['modified', 'created']);

        $this->assertDataSetsEqual($expectedDataSet, $dataSet);
    }

    /**
     * @covers ::delete
     */
    public function testDelete()
    {
        $this->markTestIncomplete(); // @todo
    }

    /**
     * @covers ::createEntitiesFromStatement
     */
    public function testCreateEntitiesFromStatement()
    {
        $this->markTestIncomplete(); // @todo
    }

    /**
     * @covers ::createCommand
     */
    public function testCreateCommand()
    {
        $this->markTestIncomplete(); // @todo
    }
}
