<?php
declare(strict_types = 1);

namespace Zortje\MVC\Model\Table\Entity;

use Zortje\MVC\Model\Table\Entity\Exception\InvalidEntityPropertyException;
use Zortje\MVC\Model\Table\Entity\Exception\InvalidValueTypeForEntityPropertyException;

/**
 * Class Entity
 *
 * @package Zortje\MVC\Model\Table\Entity
 */
abstract class Entity
{

    /**
     * @var array Columns
     */
    protected static $columns = [];

    /**
     * @var array Internal entity properties
     */
    protected $properties = [];

    /**
     * @var array Internal altered columns
     */
    protected $alteredColumns = [];

    /**
     * @param int|null  $id       Entity ID
     * @param \DateTime $modified Datetime of last modification
     * @param \DateTime $created  Datetime of creation
     */
    public function __construct($id, \DateTime $modified, \DateTime $created)
    {
        $this->set('id', $id);
        $this->set('modified', $modified);
        $this->set('created', $created);
    }

    /**
     * Get entity columns
     *
     * @return array Entity columns
     */
    public static function getColumns(): array
    {
        $columns = array_merge([
            'id' => 'integer'
        ], static::$columns);

        $columns = array_merge($columns, [
            'modified' => 'datetime',
            'created'  => 'datetime'
        ]);

        return $columns;
    }

    /**
     * Set entity property
     *
     * @param string                                      $key   Entity property name
     * @param object|int|double|string|array|boolean|null $value Entity property value
     *
     * @throws InvalidEntityPropertyException If entity does not have that property
     * @throws InvalidValueTypeForEntityPropertyException If value is of the wrong type
     */
    public function set(string $key, $value)
    {
        if (isset(self::getColumns()[$key]) === false) {
            throw new InvalidEntityPropertyException([get_class($this), $key]);
        }

        $newValue = $this->validatePropertyValueType($key, $value);

        if (!isset($this->properties[$key]) || $this->properties[$key] !== $newValue) {
            /**
             * Set internal property
             */
            $this->properties[$key] = $newValue;

            /**
             * Set altered column
             */
            $this->alteredColumns[$key] = true;
        }
    }

    /**
     * Get entity property
     *
     * @param string $key Entity property
     *
     * @return object|int|double|string|array|boolean|null Entity property value for given key
     *
     * @throws InvalidEntityPropertyException If entity does not have that property
     */
    public function get(string $key)
    {
        if (!isset(self::getColumns()[$key])) {
            throw new InvalidEntityPropertyException([get_class($this), $key]);
        }

        return $this->properties[$key];
    }

    /**
     * Validate type for given property value
     *
     * @param string $key   Entity property name
     * @param mixed  $value Entity property value
     *
     * @return object|int|double|string|array|boolean|null Value
     *
     * @throws InvalidEntityPropertyException If entity does not have that property
     * @throws InvalidValueTypeForEntityPropertyException If value is of the wrong type
     */
    public function validatePropertyValueType(string $key, $value)
    {
        if (!isset(self::getColumns()[$key])) {
            throw new InvalidEntityPropertyException([get_class($this), $key]);
        }

        /**
         * Allow NULL
         */
        if ($value !== null) {
            $valueType = strtolower(gettype($value));

            /**
             * Get class if object
             */
            if ($valueType === 'object') {
                $valueType = strtolower(get_class($value));
            }

            /**
             * Handle alias types
             */
            $columnType = self::getColumns()[$key];

            switch ($columnType) {
                case 'date':
                    $columnType = 'datetime';
                    break;

                case 'varbinary':
                    $columnType = 'string';
                    break;
            }

            /**
             * Validate type
             */
            if ($valueType !== $columnType) {
                throw new InvalidValueTypeForEntityPropertyException([
                    get_class($this),
                    $key,
                    $columnType,
                    $valueType
                ]);
            }
        }

        return $value;
    }

    /**
     * Check if entity has been altered
     *
     * @return bool True if altered, otherwise false
     */
    public function isAltered(): bool
    {
        return count($this->alteredColumns) > 0;
    }

    /**
     * Marks the entity as unaltered
     */
    public function setUnaltered()
    {
        $this->alteredColumns = [];
    }

    /**
     * Get altered columns
     *
     * @return array
     */
    public function getAlteredColumns(): array
    {
        return $this->alteredColumns;
    }

    /**
     * Return table structure for saving
     * Example: `[':{table_field_name}' => $this->fieldName]`
     *
     * @param bool $includeId Should the ID column be included
     *
     * @return array
     */
    public function toArray(bool $includeId): array
    {
        $columns = self::getColumns();

        if (!$includeId) {
            unset($columns['id']);
        }

        return $this->toArrayFromColumns($columns);
    }

    /**
     * Return table structure for saving just altered columns
     *
     * @param bool $includeId Should the ID column be included
     *
     * @return array
     */
    public function alteredToArray(bool $includeId): array
    {
        $alteredColumns = $this->alteredColumns;

        if ($includeId) {
            $alteredColumns['id'] = true;
        }

        return $this->toArrayFromColumns(array_intersect_key(self::getColumns(), $alteredColumns));
    }

    /**
     * Return columns in structure for saving
     *
     * @param array $columns Columns to include
     *
     * @return array
     */
    protected function toArrayFromColumns(array $columns): array
    {
        $array = [];

        foreach ($columns as $column => $type) {
            $property = new EntityProperty($type);

            $value = $this->get($column);
            $value = $property->formatValueForDatabase($value);

            $array[":$column"] = $value;
        }

        return $array;
    }
}
