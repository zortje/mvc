<?php
declare(strict_types = 1);

namespace Zortje\MVC\Network;

/**
 * Class Response
 *
 * @package Zortje\MVC\Network
 */
class Response
{

    /**
     * @var array HTTP headers
     */
    protected $headers = [];

    /**
     * @var string Output
     */
    protected $output;

    /**
     * @param array  $headers
     * @param string $output
     */
    public function __construct(array $headers, string $output)
    {
        $this->headers = $headers;
        $this->output  = $output;
    }

    /**
     * @return array
     */
    public function output(): array
    {
        return [
            'headers' => $this->headers,
            'output'  => $this->output
        ];
    }
}
