<?php
declare(strict_types = 1);

namespace Zortje\MVC\Model\Table\Entity\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class EntityPropertyTypeNonexistentException
 *
 * @package Zortje\MVC\Model\Table\Entity\Exception
 */
class EntityPropertyTypeNonexistentException extends Exception
{

    /**
     * {@inheritdoc}
     */
    protected $template = 'Entity property type "%s" is not supported';
}
