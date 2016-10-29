<?php
declare(strict_types = 1);

namespace Zortje\MVC\Model\Table\Entity\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class EntityPropertyTypeNotImplementedException
 *
 * @package Zortje\MVC\Model\Table\Entity\Exception
 */
class EntityPropertyTypeNotImplementedException extends Exception
{

    /**
     * {@inheritdoc}
     */
    protected $template = '"%s" is not an implemented entity property type';
}
