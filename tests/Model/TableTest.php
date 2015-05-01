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
class TableTest extends \PHPUnit_Framework_TestCase {

	private $pdo;

	public function setUp() {
		$this->pdo = new \PDO("mysql:host=127.0.0.1;dbname=myapp_test", 'root', '');
	}

	/**
	 * @covers ::getTableName
	 */
	public function testGetTableName() {
		$carTable = new CarTable($this->pdo);

		$this->assertSame('cars', $carTable->getTableName());
	}

}
