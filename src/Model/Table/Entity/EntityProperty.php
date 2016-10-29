<?php
declare(strict_types = 1);

namespace Zortje\MVC\Model\Table\Entity;

use Ramsey\Uuid\Uuid;
use Zortje\MVC\Model\Table\Entity\Exception\EntityPropertyTypeNonexistentException;
use Zortje\MVC\Model\Table\Entity\Exception\EntityPropertyTypeNotImplementedException;
use Zortje\MVC\Model\Table\Entity\Exception\EntityPropertyValueExceedingLengthException;
use Zortje\MVC\Model\Table\Entity\Exception\InvalidENUMValueForEntityPropertyException;
use Zortje\MVC\Model\Table\Entity\Exception\InvalidUUIDValueForEntityPropertyException;
use Zortje\MVC\Model\Table\Entity\Exception\InvalidValueTypeForEntityPropertyException;

/**
 * Class EntityProperty
 *
 * @package Zortje\MVC\Model\Table\Entity
 */
class EntityProperty
{

    const STRING = 'string';
    const INTEGER = 'integer';
    const FLOAT = 'float';
    const DOUBLE = 'double';
    const BOOL = 'bool';

    const DATE = 'date';
    const DATETIME = 'datetime';

    const VARBINARY = 'varbinary';

    const UUID = 'uuid';

    const ENUM = 'enum';

    /**
     * @var string Entity property type
     */
    protected $type;

    /**
     * @var int Entity property max length
     */
    protected $length;

    /**
     * @var array Allowed values
     */
    protected $values;

    /**
     * @param string|array $type
     */
    public function __construct($type)
    {
        if (is_array($type)) {
            /**
             * Type
             */
            if (!isset($type['type'])) {
                throw new \InvalidArgumentException('Index "type" not found in parameter array');
            }

            $this->setType($type['type']);

            /**
             * Length
             */
            if (isset($type['length']) && is_numeric($type['length'])) {
                $this->length = (int)$type['length'];
            }

            /**
             * Values
             */
            if (isset($type['values']) && is_array($type['values'])) {
                $this->values = array_fill_keys($type['values'], true);
            }
        } else {
            $this->setType($type);
        }
    }

    /**
     * Set entity property type
     *
     * @param string $type
     *
     * @throws EntityPropertyTypeNonexistentException
     */
    protected function setType(string $type)
    {
        if (!defined(EntityProperty::class . '::' . strtoupper($type))) {
            throw new EntityPropertyTypeNonexistentException([$type]);
        }

        $this->type = $type;
    }

    /**
     * Validate value for entity property
     *
     * @param mixed $value Entity property value
     *
     * @return bool TRUE if valid, otherwise FALSE
     *
     * @throws EntityPropertyTypeNotImplementedException If entity property type is not implemented
     * @throws EntityPropertyValueExceedingLengthException If value is exceeding allowed length for entity property
     * @throws InvalidENUMValueForEntityPropertyException If ENUM value is invalid for entity property
     * @throws InvalidUUIDValueForEntityPropertyException If UUID value is invalid for entity property
     * @throws InvalidValueTypeForEntityPropertyException If value is invalid for entity property
     */
    public function validateValue($value): bool
    {
        if (is_null($value)) {
            return true;
        }

        switch ($this->type) {
            case self::STRING:
                if (!is_string($value)) {
                    throw new InvalidValueTypeForEntityPropertyException([gettype($value), $this->type]);
                }

                /**
                 * Check length
                 */
                $length = strlen($value);

                if (!is_null($this->length) && $length > $this->length) {
                    throw new EntityPropertyValueExceedingLengthException($value, $length);
                }

                break;

            case self::INTEGER:
                if (!is_int($value)) {
                    throw new InvalidValueTypeForEntityPropertyException([gettype($value), $this->type]);
                }

                break;

            case self::FLOAT:
                if (!is_float($value)) {
                    throw new InvalidValueTypeForEntityPropertyException([gettype($value), $this->type]);
                }

                break;

            case self::DOUBLE:
                if (!is_double($value)) {
                    throw new InvalidValueTypeForEntityPropertyException([gettype($value), $this->type]);
                }

                break;

            case self::BOOL:
                if (!is_bool($value)) {
                    throw new InvalidValueTypeForEntityPropertyException([gettype($value), $this->type]);
                }

                break;

            case self::DATE:
            case self::DATETIME:
                if (!is_object($value) || (is_object($value) && get_class($value) !== \DateTime::class)) {
                    throw new InvalidValueTypeForEntityPropertyException([gettype($value), $this->type]);
                }

                break;

            case self::VARBINARY:
                // @todo Implement this

                break;

            case self::UUID:
                if (!is_string($value)) {
                    throw new InvalidValueTypeForEntityPropertyException([gettype($value), $this->type]);
                }

                /**
                 * Check UUID
                 */
                if (!Uuid::isValid($value)) {
                    throw new InvalidUUIDValueForEntityPropertyException($value);
                }

                break;

            case self::ENUM:
                if (!is_string($value)) {
                    throw new InvalidValueTypeForEntityPropertyException([gettype($value), $this->type]);
                }

                /**
                 * Check values
                 */
                if (!isset($this->values[$value])) {
                    throw new InvalidENUMValueForEntityPropertyException($value);
                }

                break;

            default:
                throw new EntityPropertyTypeNotImplementedException($this->type);
                break;
        }

        return true;
    }

    /**
     * Format value according to entity property type
     *
     * @param mixed $value Value
     *
     * @return mixed Value
     *
     * @throws EntityPropertyTypeNotImplementedException If entity property type is not implemented
     */
    public function formatValueForEntity($value)
    {
        switch ($this->type) {
            case self::STRING:
            case self::UUID:
                $value = "$value";
                break;

            case self::INTEGER:
                $value = (int)$value;
                break;

            case self::FLOAT:
            case self::DOUBLE:
                $value = (float)$value;
                break;

            case self::DATE:
            case self::DATETIME:
                $value = new \DateTime($value);
                break;

            case self::VARBINARY:
                // @todo Implement this

                break;

            case self::BOOL:
                $value = $value === '1'; // @todo test that this works
                break;

            case self::ENUM:
                // @todo Implement this

                break;

            default:
                throw new EntityPropertyTypeNotImplementedException($this->type);
                break;
        }

        return $value;
    }

    /**
     * Format value for insertion into the database
     *
     * @param mixed $value Value
     *
     * @return mixed Value
     */
    public function formatValueForDatabase($value)
    {
        switch ($this->type) {
            case self::DATE:
                /**
                 * @var \DateTime $value
                 */
                $value = $value->format('Y-m-d');
                break;

            case self::DATETIME:
                /**
                 * @var \DateTime $value
                 */
                $value = $value->format('Y-m-d H:i:s');
                break;
        }

        return $value;
    }
}
