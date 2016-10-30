<?php
declare(strict_types = 1);

namespace Zortje\MVC\Model\Table\Entity\Exception;

/**
 * Class InvalidUUIDValueForEntityPropertyException
 *
 * @package Zortje\MVC\Model\Table\Entity\Exception
 */
class InvalidUUIDValueForEntityPropertyException extends EntityPropertyValueException
{

    /**
     * {@inheritdoc}
     */
    protected $template = '"%s" is not a valid UUID value';
}
