<?php
declare(strict_types = 1);

namespace Zortje\MVC\Storage\Cookie\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class CookieUndefinedIndexException
 *
 * @package Zortje\MVC\Storage\Cookie\Exception
 */
class CookieUndefinedIndexException extends Exception
{

    /**
     * {@inheritdoc}
     */
    protected $template = 'Cookie key %s is undefined';
}
