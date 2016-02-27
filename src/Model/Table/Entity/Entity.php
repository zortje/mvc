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
     * @param int       $id
     * @param \DateTime $modified
     * @param \DateTime $created
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
            'modified' => 'DateTime',
            'created'  => 'DateTime'
        ]);

        return $columns;
    }

    /**
     * Set entity property
     *
     * @param string                                          $key   Entity property name
     * @param object|integer|double|string|array|boolean|null $value Entity property value
     *
     * @throws InvalidEntityPropertyException If entity does not have that property
     * @throws InvalidValueTypeForEntityPropertyException If value is of the wrong type
     */
    public function set(string $key, $value)
    {
        if (!isset(self::getColumns()[$key])) {
            throw new InvalidEntityPropertyException([get_class($this), $key]);
        }

        $this->properties[$key] = $this->validatePropertyForValue($key, $value);
    }

    /**
     * Get entity property
     *
     * @param string $key Entity property
     *
     * @return object|integer|double|string|array|boolean|null Entity property value for given key
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
     * Return table structur for saving
     * Example: `['table_field_name' => $this->fieldName]`
     *
     * @param bool $includeId Should the ID column be included
     *
     * @return array
     */
    public function toArray(bool $includeId): array
    {
        $array = [];

        foreach (self::getColumns() as $column => $type) {
            if ($column === 'id' && !$includeId) {
                continue;
            }

            $property = new EntityProperty($type);

            $value = $this->get($column);
            $value = $property->formatValueForDatabase($value);

            $array[":$column"] = $value;
        }

        return $array;
    }

    /**
     * Validate property for given value
     *
     * @param string $key   Entity property name
     * @param mixed  $value Entity property value
     *
     * @return object|integer|double|string|array|boolean|null Value
     *
     * @throws InvalidEntityPropertyException If entity does not have that property
     * @throws InvalidValueTypeForEntityPropertyException If value is of the wrong type
     */
    protected function validatePropertyForValue(string $key, $value)
    {
        if (!isset(self::getColumns()[$key])) {
            throw new InvalidEntityPropertyException([get_class($this), $key]);
        }

        /**
         * Allow NULL
         */
        if ($value !== null) {
            $valueType = gettype($value);

            /**
             * Get class if object
             */
            if ($valueType === 'object') {
                $valueType = get_class($value);
            }

            /**
             * Handle alias types
             */
            $columnType = self::getColumns()[$key];

            switch ($columnType) {
                case 'Date':
                    $columnType = 'DateTime';
                    break;
            }

            /**
             * Validate type
             */
            if ($valueType !== $columnType) {
                throw new InvalidValueTypeForEntityPropertyException([
                    get_class($this),
                    $key,
                    $valueType,
                    $columnType
                ]);
            }
        }

        return $value;
    }
}
