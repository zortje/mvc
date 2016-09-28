<?php
declare(strict_types = 1);

namespace Zortje\MVC\Model\Table\Entity;

use Ramsey\Uuid\Uuid;
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
     * @param string|null $uuid     Entity ID
     * @param \DateTime   $modified Datetime of last modification
     * @param \DateTime   $created  Datetime of creation
     */
    public function __construct($uuid, \DateTime $modified, \DateTime $created)
    {
        $this->set('uuid', $uuid ?: Uuid::uuid1()->toString());
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
            'uuid' => 'uuid'
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
             * Handle UUID type
             */
            if ($columnType === 'uuid' && Uuid::isValid($value)) {
                $valueType = 'uuid';
            }

            /**
             * Validate type
             */
            if ($valueType !== $columnType) {
                throw new InvalidValueTypeForEntityPropertyException([get_class($this), $key, $valueType, $columnType]);
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
     * @return array
     */
    public function toArray(): array
    {
        $columns = self::getColumns();

        return $this->toArrayFromColumns($columns);
    }

    /**
     * Return table structure for saving just altered columns
     *
     * @param bool $includeUuid Should the UUID column be included
     *
     * @return array
     */
    public function alteredToArray(bool $includeUuid): array
    {
        $alteredColumns = $this->alteredColumns;

        if ($includeUuid) {
            $alteredColumns['uuid'] = true;
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
