<?php
declare(strict_types = 1);

namespace Zortje\MVC\Network;

use Zortje\MVC\Storage\Cookie\Cookie;

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
     * @var Cookie
     */
    protected $cookie;

    /**
     * @var string Output
     */
    protected $output;
    
    /**
     * Response constructor.
     *
     * @param array       $headers
     * @param Cookie|null $cookie
     * @param string      $output
     */
    public function __construct(array $headers, Cookie $cookie = null, string $output)
    {
        $this->headers = $headers;
        $this->cookie  = $cookie;
        $this->output  = $output;
    }

    /**
     * Get response headers as an array, to be set in the index.php file
     *
     * ```
     * foreach ($response->getHeaders() as $header) {
     *     header($header);
     * }
     * ```
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get response cookie, to be set in the index.php file
     *
     * ```
     * setcookie('token', $response->getCookie->getTokenString(), time() + 3600, '/', '', true, true);
     * ```
     *
     * @return Cookie|null
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * Get response output, to be echoed in the index.php file
     *
     * ```
     * echo $response->getOutput();
     * ```
     *
     * @return string
     */
    public function getOutput(): string
    {
        return $this->output;
    }
}
