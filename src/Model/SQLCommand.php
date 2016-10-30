<?php
declare(strict_types = 1);

namespace Zortje\MVC\Model;

use Zortje\MVC\Model\Table\Entity\Exception\InvalidEntityPropertyException;

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
     * Get table name
     *
     * @return String Table name
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * Get table columns
     *
     * @return \String[] Columns
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Create INSERT INTO command
     *
     * @return string INSERT INTO query
     */
    public function insertInto(): string
    {
        $columnNames  = $this->getColumnNames($this->columns);
        $columnValues = $this->getColumnValues($this->columns);

        return "INSERT INTO `$this->tableName` ($columnNames) VALUES ($columnValues);";
    }

    /**
     * Create UPDATE SET command with WHERE for updating a single row with ID
     *
     * @param array $columns Columns to use in SET condition
     *
     * @return string
     */
    public function updateSetWhere(array $columns): string
    {
        $set   = $this->getEqualFromColumns(', ', $columns);
        $where = $this->getEqualFromColumns(' AND ', ['id']);

        return "UPDATE `$this->tableName` SET $set WHERE $where;";
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
     * @param string[] $columns Columns to use in WHERE condition
     *
     * @return string SELECT FROM query
     */
    public function selectFromWhere($columns): string
    {
        $tableColumnNames = $this->getColumnNames($this->columns);

        $where = $this->getEqualFromColumns(' AND ', $columns);

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
     * Get equal string for columns with glue
     *
     * @param string $glue    String glue between columns
     * @param array  $columns Columns to use
     *
     * @return string
     *
     * @throws InvalidEntityPropertyException If provided with columns which the entity dosnt have
     */
    protected function getEqualFromColumns(string $glue, array $columns): string
    {
        $equal = [];

        foreach ($columns as $column) {
            if (!isset($this->columns[$column])) {
                throw new InvalidEntityPropertyException([$this->tableName, $column]);
            }

            $equal[] = "`$column` = :$column";
        }

        return implode($glue, $equal);
    }
}
