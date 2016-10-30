<?php
declare(strict_types = 1);

namespace Zortje\MVC\Model\Table\Entity\Exception;

/**
 * Class InvalidIPAddressValueForEntityPropertyException
 *
 * @package Zortje\MVC\Model\Table\Entity\Exception
 */
class InvalidIPAddressValueForEntityPropertyException extends EntityPropertyValueException
{

    /**
     * {@inheritdoc}
     */
    protected $template = '"%s" is not a valid IP address';
}
