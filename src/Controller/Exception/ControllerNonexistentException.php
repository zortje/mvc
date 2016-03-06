<?php
declare(strict_types = 1);

namespace Zortje\MVC\Controller\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class ControllerNonexistentException
 *
 * @package Zortje\MVC\Controller\Exception
 */
class ControllerNonexistentException extends Exception
{

    /**
     * {@inheritdoc}
     */
    protected $template = 'Controller %s is nonexistent';
}
