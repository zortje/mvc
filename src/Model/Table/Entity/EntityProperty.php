<?php
declare(strict_types = 1);

namespace Zortje\MVC\Model\Table\Entity;

/**
 * Class EntityProperty
 *
 * @package Zortje\MVC\Model\Table\Entity
 */
class EntityProperty
{

    /**
     * @var string Entity property type
     */
    protected $type;

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Format value according to entity property type
     *
     * @param mixed $value Value
     *
     * @return mixed Value
     */
    public function formatValueForEntity($value)
    {
        switch ($this->type) {
            case 'string':
                $value = "$value";
                break;

            case 'integer':
                $value = (int) $value;
                break;

            case 'float':
                $value = (float) $value;
                break;

            case 'Date':
            case 'DateTime':
                $value = new \DateTime($value);
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
            case 'Date':
                /**
                 * @var \DateTime $value
                 */
                $value = $value->format('Y-m-d');
                break;

            case 'DateTime':
                /**
                 * @var \DateTime $value
                 */
                $value = $value->format('Y-m-d H:i:s');
                break;
        }

        return $value;
    }
}
