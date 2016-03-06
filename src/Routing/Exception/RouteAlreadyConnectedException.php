<?php
declare(strict_types = 1);

namespace Zortje\MVC\Routing\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class RouteAlreadyConnectedException
 *
 * @package Zortje\MVC\Routing\Exception
 */
class RouteAlreadyConnectedException extends Exception
{

    /**
     * {@inheritdoc}
     */
    protected $template = 'Route %s is already connected';
}
