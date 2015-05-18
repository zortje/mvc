<?php

namespace Zortje\MVC\Tests\Controller\Fixture;

use Zortje\MVC\Controller\Controller;

/**
 * Class CarsController
 *
 * @package Zortje\MVC\Tests\Controller\Fixture
 */
class CarsController extends Controller {

	protected $access = [
		'list'        => Controller::ACTION_PUBLIC,
		'hidden-list' => Controller::ACTION_PROTECTED,
		'add'         => Controller::ACTION_PRIVATE
	];

}
