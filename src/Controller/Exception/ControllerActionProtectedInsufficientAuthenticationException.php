<?php

namespace Zortje\MVC\Controller\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class ControllerActionProtectedInsufficientAuthenticationException
 *
 * @package Zortje\MVC\Controller\Exception
 */
class ControllerActionProtectedInsufficientAuthenticationException extends Exception
{

    /**
     * {@inheritdoc}
     */
    protected $template = 'Controller %s protected action %s requires authentication';

    /**
     * {@inheritdoc}
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }

}
