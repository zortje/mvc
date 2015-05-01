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

	private $pdo;

	public function setUp() {
		$this->pdo = new \PDO("mysql:host=127.0.0.1;dbname=myapp_test", 'root', '');
	}

	/**
	 * @covers ::insertInto
	 */
	public function testInsertInto() {
		$table  = new CarTable($this->pdo);
		$entity = new CarEntity('Ford', 'Model T');

		$sqlCommand = new SQLCommand($table, $entity);

		$expected = 'INSERT INTO `cars` (`id`, `make`, `model`, `modified`, `created`) VALUES (NULL, :make, :model, :modified, :created);';

		$this->assertSame($expected, $sqlCommand->insertInto());
	}

}
