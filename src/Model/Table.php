<?php

namespace Zortje\MVC\Model;

use Zortje\MVC\Model\Exception\TableNameNotDefinedException;
use Zortje\MVC\Model\Exception\EntityClassNotDefinedException;
use Zortje\MVC\Model\Exception\EntityClassNonexistentException;

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
	 * @var String Entity class
	 */
	protected $entityClass;

	/**
	 * Get table name
	 *
	 * @return string Table name
	 */
	public function getTableName() {
		return $this->tableName;
	}

	/**
	 * Find all entities
	 *
	 * @return Entity[] Entities
	 */
	public function findAll() {
		$entities = [];

		$command = $this->createCommand();

		$stmt = $this->pdo->prepare($command->selectFrom());
		$stmt->execute();

		$entityFactory = new EntityFactory($this->entityClass);

		foreach ($stmt as $row) {
			$entities[] = $entityFactory->createFromArray($row);
		}

		return $entities;
	}

	public function select($entityId) {
		//
	}

	/**
	 * @param Entity $entity
	 */
	public function insert(Entity $entity) {
		$command = $this->createCommand();

		$stmt = $this->pdo->prepare($command->insertInto());

		var_dump($entity);

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
	 * @return SQLCommand
	 */
	protected function createCommand() {
		$reflector = new \ReflectionClass($this->entityClass);

		$entity = $reflector->newInstanceWithoutConstructor();

		$columns = $entity::getColumns();

		return new SQLCommand($this->tableName, $columns);
	}

	/**
	 * @param \PDO $pdo
	 *
	 * @throws TableNameNotDefinedException If table name is not defined in subclass
	 * @throws EntityClassNotDefinedException If entity class is not defined in subclass
	 * @throws EntityClassNonexistentException If entity class is nonexistent
	 */
	public function __construct(\PDO $pdo) {
		if (is_null($this->tableName)) {
			throw new TableNameNotDefinedException([get_class($this)]);
		}

		if (is_null($this->entityClass)) {
			throw new EntityClassNotDefinedException([get_class($this)]);
		} else if (!class_exists($this->entityClass)) {
			throw new EntityClassNonexistentException([get_class($this), $this->entityClass]);
		}

		$this->pdo = $pdo;
	}

}
