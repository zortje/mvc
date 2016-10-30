<?php
declare(strict_types = 1);

namespace Zortje\MVC\Model\Table\Entity\Exception;

/**
 * Class EntityPropertyValueExceedingLengthException
 *
 * @package Zortje\MVC\Model\Table\Entity\Exception
 */
class EntityPropertyValueExceedingLengthException extends EntityPropertyValueException
{

    /**
     * {@inheritdoc}
     */
    protected $template = '"%s" is longer than %s characters';
}
