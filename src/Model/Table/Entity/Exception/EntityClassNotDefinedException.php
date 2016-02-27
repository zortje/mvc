<?php
declare(strict_types = 1);

namespace Zortje\MVC\Model\Table\Entity\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class EntityClassNotDefinedException
 *
 * @package Zortje\MVC\Model\Table\Entity\Exception
 */
class EntityClassNotDefinedException extends Exception
{

    /**
     * {@inheritdoc}
     */
    protected $template = 'Subclass %s does not have a entity class defined';

    /**
     * {@inheritdoc}
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
