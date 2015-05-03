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
	 * @var SQLCommand SQL Command
	 */
	protected $sqlCommand;

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
		$stmt = $this->pdo->prepare($this->sqlCommand->selectFrom());
		$stmt->execute();

		return $this->createEntitiesFromStatement($stmt);
	}

	/**
	 * Find all entities where key is equal to the given value
	 *
	 * @param $key
	 * @param $value
	 *
	 * @return Entity[] Entities
	 */
	public function findBy($key, $value) {
		$stmt = $this->pdo->prepare($this->sqlCommand->selectFromWhere($key));
		$stmt->execute([":$key" => $value]);

		return $this->createEntitiesFromStatement($stmt);
	}

	public function select($entityId) {
		//
	}

	/**
	 * @param Entity $entity
	 */
	public function insert(Entity $entity) {
		$stmt = $this->pdo->prepare($this->sqlCommand->insertInto());

		$now = new \DateTime();
		$now = $now->format('Y-m-d H:i:s');

		$array = array_merge($entity->toArray(false), [
			'modified' => $now,
			'created'  => $now
		]);

		/*
		unset($array['id']);

		foreach ($array as $key => $val) {
			$array[":$key"] = $val;

			unset($array[$key]);
		}
		*/

		$stmt->execute($array);

		return (int) $this->pdo->lastInsertId();
	}

	public function update(Entity $entity) {
		//
	}

	public function delete(Entity $entity) {
		//
	}

	/**
	 * Creates an array of Entity objects from statement
	 *
	 * @param \PDOStatement $statement
	 *
	 * @return Entity[] Entities from statement
	 */
	protected function createEntitiesFromStatement(\PDOStatement $statement) {
		$entities = [];

		$entityFactory = new EntityFactory($this->entityClass);

		foreach ($statement as $row) {
			$entities[] = $entityFactory->createFromArray($row);
		}

		return $entities;
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

		$this->sqlCommand = $this->createCommand();
	}

}
