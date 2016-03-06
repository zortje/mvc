<?php
declare(strict_types = 1);

namespace Zortje\MVC\Configuration\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class ConfigurationNonexistentException
 *
 * @package Zortje\MVC\Configuration\Exception
 */
class ConfigurationNonexistentException extends Exception
{

    /**
     * {@inheritdoc}
     */
    protected $template = 'Configuration %s is nonexistent';
}
