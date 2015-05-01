<?php

namespace Zortje\MVC\Tests\Model\Fixture;

use Zortje\MVC\Model\Entity;

/**
 * Class CarEntity
 *
 * @package Zortje\MVC\Tests\Model\Fixture
 */
class CarEntity extends Entity {

	protected $make;
	protected $model;

	/**
	 * @return array
	 */
	public function toArray() {
		return [
			'make'  => $this->make,
			'model' => $this->model
		];
	}

	/**
	 * @param string $make
	 * @param string $model
	 */
	public function __construct($make, $model) {
		parent::__construct(null, new \DateTime(), new \DateTime());

		$this->make  = $make;
		$this->model = $model;
	}

}
