<?php
declare(strict_types = 1);

namespace Zortje\MVC\Model;

/**
 * Class SQLCommand
 *
 * @package Zortje\MVC\Model
 */
class SQLCommand
{

    /**
     * @var String Table name
     */
    private $tableName;

    /**
     * @var String[] Table columns
     */
    private $columns;

    /**
     * @param string   $tableName
     * @param string[] $columns
     */
    public function __construct(string $tableName, array $columns)
    {
        $this->tableName = $tableName;
        $this->columns   = $columns;
    }

    /**
     * Create INSERT INTO command
     *
     * @return string INSERT INTO query
     */
    public function insertInto(): string
    {
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
    public function selectFrom(): string
    {
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
    public function selectFromWhere($keys): string
    {
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
    protected function getColumnNames(array $columns): string
    {
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
    protected function getColumnValues(array $columns): string
    {
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
    protected function getWhereConditionFromKeys($keys): string
    {
        $where = [];

        if (is_string($keys)) {
            $keys = [$keys];
        } elseif (!is_array($keys)) {
            throw new \InvalidArgumentException('Keys must be a string or an array of strings');
        }

        foreach ($keys as $key) {
            $where[] = "`$key` = :$key";
        }

        return implode(' AND ', $where);
    }
}
