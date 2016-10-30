<?php
declare(strict_types = 1);

namespace Zortje\MVC\Model\Table\Entity\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class EntityClassInvalidSuperclassException
 *
 * @package Zortje\MVC\Model\Table\Entity\Exception
 */
class EntityClassInvalidSuperclassException extends Exception
{

    /**
     * {@inheritdoc}
     */
    protected $template = 'Entity class %s is not extending Zortje\MVC\Model\Table\Entity\Entity';
}
