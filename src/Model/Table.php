<?php

namespace Zortje\MVC\Model;

/**
 * Class Table
 *
 * @package Zortje\MVC\Model
 */
class Table {

	/**
	 * @var \PDO Connection
	 */
	protected $pdo;

	/**
	 * @param \PDO $pdo
	 */
	public function __construct(\PDO $pdo) {
		$this->pdo = $pdo;
	}

	//public function insert(Entity $entity, );

	//public function update(Entity $entity, \PDO $pdo);

	//public function select($entityId, \PDO $pdo);

}
