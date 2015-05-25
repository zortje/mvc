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

	protected $layout = '../tests/View/Layout/Fixture/auto';

	protected function index() {
		$this->view = '../tests/View/Cars/Fixture/index';

		$carTable = new CarTable($this->pdo);

		$this->set('cars', $carTable->findAll());
	}

	protected function hidden() {
		$this->view = '../tests/View/Cars/Fixture/hidden';

		// @todo Implement
	}

	protected function add() {
		$this->view = '../tests/View/Cars/Fixture/add';

		// @todo Implement
	}

}
