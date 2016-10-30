<?php
declare(strict_types = 1);

namespace Zortje\MVC\Model\Table\Entity\Exception;

/**
 * Class InvalidENUMValueForEntityPropertyException
 *
 * @package Zortje\MVC\Model\Table\Entity\Exception
 */
class InvalidENUMValueForEntityPropertyException extends EntityPropertyValueException
{

    /**
     * {@inheritdoc}
     */
    protected $template = '"%s" is not a valid ENUM value';
}
