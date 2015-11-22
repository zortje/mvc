<?php

namespace Zortje\MVC\Network;

/**
 * Class Request
 *
 * @package Zortje\MVC\Network
 */
class Request
{

    /**
     * @var string Full URL
     */
    protected $url;

    /**
     * @var array POST data
     */
    protected $post;

    /**
     * Get request URL path
     *
     * @return string URL path
     */
    public function getPath()
    {
        $path = parse_url($this->url, PHP_URL_PATH);

        if ($path === false) {
            $path = '';
        }

        return $path;
    }

    /**
     * @param string $url  URL of the request, trailing slash are removed automatically
     * @param array  $post URL post fields
     */
    public function __construct($url, array $post)
    {
        $this->url  = rtrim($url, '/');
        $this->post = $post;
    }
}
