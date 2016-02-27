<?php
declare(strict_types = 1);

namespace Zortje\MVC\Model\Table\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class TableNameNotDefinedException
 *
 * @package Zortje\MVC\Model\Table\Exception
 */
class TableNameNotDefinedException extends Exception
{

    /**
     * {@inheritdoc}
     */
    protected $template = 'Subclass %s does not have a table name defined';

    /**
     * {@inheritdoc}
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
