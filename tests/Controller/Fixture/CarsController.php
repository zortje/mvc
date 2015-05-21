<?php

namespace Zortje\MVC\Tests\Controller\Fixture;

use Zortje\MVC\Controller\Controller;
use Zortje\MVC\Tests\Model\Fixture\CarTable;

/**
 * Class CarsController
 *
 * @package Zortje\MVC\Tests\Controller\Fixture
 */
class CarsController extends Controller {

	protected $access = [
		'index'  => Controller::ACTION_PUBLIC,
		'hidden' => Controller::ACTION_PROTECTED,
		'add'    => Controller::ACTION_PRIVATE
	];

	protected function index() {
		$carTable = new CarTable($this->pdo);

		$this->set('cars', $carTable->findAll());
	}

	protected function hidden() {
		// @todo Implement
	}

	protected function add() {
		// @todo Implement
	}

}
