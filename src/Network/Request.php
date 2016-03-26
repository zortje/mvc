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
     * @var Cookie Cookie
     */
    protected $cookie;

    /**
     * @var string Full URL
     */
    protected $url;

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
        $this->cookie = $cookie;
        $this->url    = $this->createUrlFromServerArray($server, !empty($server['HTTPS']));
        $this->post   = $post;
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
     * Get cookie
     *
     * @return Cookie
     */
    public function getCookie(): Cookie
    {
        return $this->cookie;
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
}
