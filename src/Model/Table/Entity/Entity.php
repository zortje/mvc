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
     * @param string|null $id       Entity ID
     * @param \DateTime   $modified Datetime of last modification
     * @param \DateTime   $created  Datetime of creation
     */
    public function __construct($id, \DateTime $modified, \DateTime $created)
    {
        $this->set('id', $id ?: Uuid::uuid1()->toString());
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
            'id' => EntityProperty::UUID
        ], static::$columns);

        $columns = array_merge($columns, [
            'modified' => EntityProperty::DATETIME,
            'created'  => EntityProperty::DATETIME
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

        $entityProperty = new EntityProperty(self::getColumns()[$key]);

        if ($entityProperty->validateValue($value)) {
            if (!isset($this->properties[$key]) || $this->properties[$key] !== $value) {
                /**
                 * Set internal property
                 */
                $this->properties[$key] = $value;

                /**
                 * Set altered column
                 */
                $this->alteredColumns[$key] = true;
            }
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
