<?php

namespace Zortje\MVC\Model;

/**
 * Class SQLCommand
 *
 * @package Zortje\MVC\Model
 */
class SQLCommand {

	/**
	 * @var Table Table
	 */
	private $table;

	/**
	 * @var Entity Entity
	 */
	private $entity;

	/**
	 * @return string
	 */
	public function insertInto() {
		$tableName = $this->table->getTableName();

		$tableColumnNames  = implode('`, `', array_keys($this->entity->toArray()));
		$tableColumnValues = implode(', :', array_keys($this->entity->toArray()));

		return "INSERT INTO `$tableName` (`id`, `{$tableColumnNames}`, `modified`, `created`) VALUES (NULL, :{$tableColumnValues}, :modified, :created);";
	}

	/**
	 * @param Table  $table
	 * @param Entity $entity
	 */
	public function __construct(Table $table, Entity $entity) {
		$this->table  = $table;
		$this->entity = $entity;
	}

}
