<?php

namespace Zortje\MVC\Model;

	// @todo Should this really be called `class SQLQuery`

/**
 * Class SQLCommand
 *
 * @package Zortje\MVC\Model
 */
class SQLCommand {

	/**
	 * @var String Table name
	 */
	private $tableName;

	/**
	 * @var String[] Table columns
	 */
	private $columns;

	/**
	 * Create INSERT INTO command
	 *
	 * @return string INSERT INTO query
	 */
	public function insertInto() {
		$tableColumnNames = $this->getColumnNames($this->columns);

		$columns = $this->columns;
		unset($columns['id']);

		$tableColumnValues = $this->getColumnValues($columns);

		return "INSERT INTO `$this->tableName` ($tableColumnNames) VALUES (NULL, $tableColumnValues);";
	}

	/**
	 * Create SELECT FROM command
	 *
	 * @return string SELECT FROM query
	 */
	public function selectFrom() {
		$tableColumnNames = $this->getColumnNames($this->columns);

		return "SELECT $tableColumnNames FROM `$this->tableName`;";
	}

	/**
	 * Create SELECT FROM command with WHERE
	 *
	 * @param string|string[] $keys WHERE keys
	 *
	 * @return string SELECT FROM query
	 */
	public function selectFromWhere($keys) {
		$tableColumnNames = $this->getColumnNames($this->columns);

		$where = $this->getWhereConditionFromKeys($keys);

		return "SELECT $tableColumnNames FROM `$this->tableName` WHERE $where;";
	}

	/**
	 * Get columns names for SQL command
	 *
	 * @param String[] $columns
	 *
	 * @return string Column names for column list
	 */
	protected function getColumnNames($columns) {
		$tableColumnNames = implode('`, `', array_keys($columns));

		return "`{$tableColumnNames}`";
	}

	/**
	 * Get columns values for SQL command
	 *
	 * @param String[] $columns
	 *
	 * @return string Column names for column values
	 */
	protected function getColumnValues($columns) {
		$tableColumnValues = implode(', :', array_keys($columns));

		return ":{$tableColumnValues}";
	}

	/**
	 * Get WHERE condition for SQL command
	 *
	 * @param string|string[] $keys
	 *
	 * @return string
	 */
	protected function getWhereConditionFromKeys($keys) {
		$where = [];

		if (is_string($keys)) {
			$keys = [$keys];
		} else if (!is_array($keys)) {
			throw new \InvalidArgumentException('Keys must be a string or an array of strings');
		}

		foreach ($keys as $key) {
			$where[] = "`$key` = :$key";
		}

		return implode(' AND ', $where);
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
