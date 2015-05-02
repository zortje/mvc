<?php

namespace Zortje\MVC\Model;

/**
 * Class SQLCommand
 *
 * @package Zortje\MVC\Model
 */
class SQLCommand {

	/**
	 * @var String
	 */
	private $tableName;

	/**
	 * @var String[]
	 */
	private $columns;

	/**
	 * @return string INSERT INTO query
	 */
	public function insertInto() {
		$tableColumnNames  = $this->getColumnNames($this->columns);

		$columns = $this->columns;
		unset($columns['id']);

		$tableColumnValues = $this->getColumnValues($columns);

		return "INSERT INTO `$this->tableName` ($tableColumnNames) VALUES (NULL, $tableColumnValues);";
	}

	/**
	 * @return string SELECT FROM query
	 */
	public function selectFrom() {
		$tableColumnNames = $this->getColumnNames($this->columns);

		return "SELECT $tableColumnNames FROM `$this->tableName`;";
	}

	/*
	public function selectFromWhere($where) {

	}
	*/

	/**
	 * @param String[] $columns
	 *
	 * @return string Column names for column list
	 */
	protected function getColumnNames($columns) {
		$tableColumnNames = implode('`, `', array_keys($columns));

		return "`{$tableColumnNames}`";
	}

	/**
	 * @param String[] $columns
	 *
	 * @return string Column names for column values
	 */
	protected function getColumnValues($columns) {
		$tableColumnValues = implode(', :', array_keys($columns));

		return ":{$tableColumnValues}";
	}

	/**
	 * @param String   $tableName
	 * @param String[] $columns
	 */
	public function __construct($tableName, $columns) {
		$this->tableName = $tableName;
		$this->columns   = $columns;
	}

}
