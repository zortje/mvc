<?php
declare(strict_types = 1);

namespace Zortje\MVC\Common\Exception;

/**
 * Class Exception
 *
 * @package Zortje\MVC\Common\Exception
 */
class Exception extends \Exception
{

    /**
     * @var string
     */
    protected $template = '';

    /**
     * @param string|array|null $message Exception message
     * @param int               $code    Exception Code
     * @param null|\Exception   $previous
     */
    public function __construct($message, int $code = 0, \Exception $previous = null)
    {
        if (is_array($message)) {
            $message = vsprintf($this->template, $message);
        }

        parent::__construct($message, $code, $previous);
    }
}
