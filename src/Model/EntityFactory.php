<?php

namespace Zortje\MVC\Model;

/**
 * Class EntityFactory
 *
 * @package Zortje\MVC\Model
 */
class EntityFactory {

	/**
	 * @var String Entity class
	 */
	protected $entityClass;

	/**
	 * @param array $array
	 *
	 * @return object
	 */
	public function createFromArray(array $array) {
		/**
		 * @var Entity $entity
		 */
		$reflector = new \ReflectionClass($this->entityClass);

		$entity = $reflector->newInstanceWithoutConstructor();

		$columns = $entity::getColumns();

		$arguments = [];

		foreach ($columns as $column => $type) {
			$arguments[$column] = $array[$column]; // @todo format $row[$column] so it conform it to $type
		}

		$entity = $reflector->newInstanceArgs($arguments);

		return $entity;
	}

	/**
	 * @param string $entityClass
	 */
	public function __construct($entityClass) {
		$this->entityClass = $entityClass;
	}

}
