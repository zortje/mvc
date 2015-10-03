<?php

namespace Zortje\MVC\Controller;

/**
 * Class NotFoundController
 *
 * @package Zortje\MVC\Controller
 */
class NotFoundController extends Controller {

	protected $access = [
		'index' => Controller::ACTION_PUBLIC
	];

	protected function index() {
		$this->setResponseCode(404);
	}

}
