<?php

namespace Zortje\MVC\Controller\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class ControllerActionPrivateInsufficientAuthenticationException
 *
 * @package Zortje\MVC\Controller\Exception
 */
class ControllerActionPrivateInsufficientAuthenticationException extends Exception
{

    /**
     * {@inheritdoc}
     */
    protected $template = 'Controller %s private action %s requires authentication';

    /**
     * {@inheritdoc}
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
