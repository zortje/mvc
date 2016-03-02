<?php
declare(strict_types = 1);

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
     * @param string $url  URL of the request, trailing slash are removed automatically
     * @param array  $post URL post fields
     */
    public function __construct(string $url, array $post)
    {
        $this->url  = rtrim($url, '/');
        $this->post = $post;
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
}
