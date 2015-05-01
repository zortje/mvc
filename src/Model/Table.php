<?php

namespace Zortje\MVC\Model;

use Zortje\MVC\Model\Exception\TableNotDefinedException;

/**
 * Class Table
 *
 * @package Zortje\MVC\Model
 */
abstract class Table {

	/**
	 * @var \PDO Connection
	 */
	protected $pdo;

	/**
	 * @var string Table name
	 */
	protected $tableName;

	/**
	 * Get table name
	 *
	 * @return string Table name
	 */
	public function getTableName() {
		return $this->tableName;
	}

	public function select($entityId) {
		//
	}

	/**
	 * @param Entity $entity
	 */
	public function insert(Entity $entity) {
		$command = $this->createCommand($entity);

		$stmt = $this->pdo->prepare($command->insertInto());

		$stmt->execute([
			':modified' => date('Y-m-d H:i:s'),
			':created'  => date('Y-m-d H:i:s')
		]);

		// @todo should return the entity object with ID set (from insert_id)
	}

	public function update(Entity $entity) {
		//
	}

	public function delete(Entity $entity) {
		//
	}

	/**
	 * Create SQLCommand for this Table with provided Entity
	 *
	 * @param Entity $entity
	 *
	 * @return SQLCommand SQL Command
	 */
	protected function createCommand(Entity $entity) {
		$command = new SQLCommand($this, $entity);

		return $command;
	}

	/**
	 * @param \PDO $pdo
	 *
	 * @throws TableNotDefinedException
	 */
	public function __construct(\PDO $pdo) {
		if (is_null($this->tableName)) {
			new TableNotDefinedException();
		}

		$this->pdo = $pdo;
	}

}
