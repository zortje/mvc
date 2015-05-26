<?php

namespace Zortje\MVC\Network;

/**
 * Class Request
 *
 * @package Zortje\MVC\Network
 */
class Request {

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
	public function getPath() {
		$path = parse_url($this->url, PHP_URL_PATH);

		if (!$path) {
			$path = '';
		}

		return $path;
	}

	/**
	 * @param string $url
	 * @param array  $post
	 */
	public function __construct($url, array $post) {
		$this->url  = $url;
		$this->post = $post;
	}

}
