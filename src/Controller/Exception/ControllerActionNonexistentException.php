<?php
declare(strict_types = 1);

namespace Zortje\MVC\Controller\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class ControllerActionNonexistentException
 *
 * @package Zortje\MVC\Controller\Exception
 */
class ControllerActionNonexistentException extends Exception
{

    /**
     * {@inheritdoc}
     */
    protected $template = 'Controller %s action %s is nonexistent';
}
