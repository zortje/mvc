<?php

namespace Zortje\MVC\Model\Table\Entity;

/**
 * Class EntityFactory
 *
 * @package Zortje\MVC\Model\Table\Entity
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
		unset($columns['id'], $columns['modified'], $columns['created']);

		$arguments = [];

		foreach ($columns as $column => $type) {
			$property = new EntityProperty($type);

			$arguments[$column] = $property->formatValueForEntity($array[$column]);
		}

		$entity = $reflector->newInstanceArgs($arguments);
		$entity->set('id', (int) $array['id']);
		$entity->set('modified', new \DateTime($array['modified']));
		$entity->set('created', new \DateTime($array['created']));

		return $entity;
	}

	/**
	 * @param string $entityClass
	 */
	public function __construct($entityClass) {
		$this->entityClass = $entityClass;
	}

}
