<?php

namespace Zortje\MVC\Network;

/**
 * Class Request
 *
 * @package Zortje\MVC\Network
 */
class Request {

	protected $url;
	protected $post;

	/**
	 * @param string $url
	 * @param array  $post
	 */
	public function __construct($url, array $post) {
		$this->url  = $url;
		$this->post = $post;
	}

}
