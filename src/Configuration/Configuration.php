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

    /**
     * Configuration constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        foreach ($config as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Set configuration
     *
     * @param string $key   Configuration name
     * @param mixed  $value Configuration value
     */
    public function set(string $key, $value)
    {
        $this->configurations[$key] = $value;
    }

    /**
     * Get configuration
     *
     * @param string $key Configuration name
     *
     * @return mixed Configuration value
     *
     * @throws ConfigurationNonexistentException If configuration has not been set
     */
    public function get(string $key)
    {
        if (isset($this->configurations[$key]) === false) {
            throw new ConfigurationNonexistentException([$key]);
        }

        return $this->configurations[$key];
    }
}
