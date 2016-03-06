<?php
declare(strict_types = 1);

namespace Zortje\MVC\Configuration;

use Zortje\MVC\Configuration\Exception\ConfigurationNonexistentException;

/**
 * Class Configuration
 *
 * @package Zortje\MVC\Configuration
 */
class Configuration
{

    /**
     * @var array Internal configurations
     */
    protected $configurations = [];

    public function set(string $key, $value)
    {
        $this->configurations[$key] = $value;
    }

    public function get(string $key)
    {
        if (isset($this->configurations[$key]) === false) {
            throw new ConfigurationNonexistentException([$key]);
        }

        return $this->configurations[$key];
    }
}
