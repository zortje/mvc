<?php
declare(strict_types = 1);

namespace Zortje\MVC\Network;

use Zortje\MVC\Storage\Cookie\Cookie;

/**
 * Class Request
 *
 * @package Zortje\MVC\Network
 */
class Request
{

    /**
     * @var string Request method
     */
    protected $method;

    /**
     * @var string Full URL
     */
    protected $url;

    /**
     * @var array Request headers
     */
    protected $headers;

    /**
     * @var Cookie Cookie
     */
    protected $cookie;

    /**
     * @var array POST data
     */
    public $post;

    /**
     * Request constructor.
     *
     * @param array  $server
     * @param array  $post
     * @param Cookie $cookie
     */
    public function __construct(Cookie $cookie, array $server = [], array $post = [])
    {
        $this->method  = !empty($server['REQUEST_METHOD']) ? $server['REQUEST_METHOD'] : 'GET';
        $this->url     = $this->createUrlFromServerArray($server, !empty($server['HTTPS']));
        $this->headers = $this->parseHeaders($server);
        $this->cookie  = $cookie;
        $this->post    = $post;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get request URL path
     *
     * @return string URL path
     */
    public function getPath(): string
    {
        $path = parse_url($this->url, PHP_URL_PATH);

        if (is_string($path) === false) {
            $path = '';
        }

        return $path;
    }

    public function getAcceptHeader(): string
    {
        return $this->headers['Accept'];
    }

    public function getAuthorizationHeader(): string
    {
        return $this->headers['Authorization'];
    }

    /**
     * Get cookie
     *
     * @return Cookie
     */
    public function getCookie(): Cookie
    {
        return $this->cookie;
    }

    /**
     * Get request POST data
     *
     * @return array POST data
     */
    public function getPost(): array
    {
        return $this->post;
    }

    /**
     * Create URL from _SERVER array
     *
     * @param array $server _SERVER array
     * @param bool  $secure
     *
     * @return string URL
     */
    protected function createUrlFromServerArray(array $server, bool $secure = true): string
    {
        $protocol = $secure ? 'https' : 'http';
        $host     = !empty($server['HTTP_HOST']) ? $server['HTTP_HOST'] : 'www.example.com';
        $path     = !empty($server['REQUEST_URI']) ? $server['REQUEST_URI'] : '';

        $url = "$protocol://$host$path";

        return rtrim($url, '/');
    }

    protected function parseHeaders(array $server): array
    {
        $headers = [
            'Accept'        => '',
            'Authorization' => ''
        ];

        foreach ($server as $header => $value) {
            if (substr($header, 0, 5) == 'HTTP_') {
                $header = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($header, 5)))));

                if (isset($headers[$header])) {
                    $headers[$header] = $value;
                }
            }
        }

        return $headers;
    }
}
