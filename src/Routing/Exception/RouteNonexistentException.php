<?php

namespace Zortje\MVC\Routing\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class RouteNonexistentException
 *
 * @package Zortje\MVC\Routing\Exception
 */
class RouteNonexistentException extends Exception
{

    /**
     * {@inheritdoc}
     */
    protected $template = 'Route %s is not connected';

    /**
     * {@inheritdoc}
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
