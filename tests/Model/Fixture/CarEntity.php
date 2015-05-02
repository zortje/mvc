<?php

namespace Zortje\MVC\Tests\Model\Fixture;

use Zortje\MVC\Model\Entity;

/**
 * Class CarEntity
 *
 * @package Zortje\MVC\Tests\Model\Fixture
 */
class CarEntity extends Entity {

	protected static $columns = [
		'make'  => 'string',
		'model' => 'string'
	];

	/**
	 * @param string $make
	 * @param string $model
	 */
	public function __construct($make, $model) {
		parent::__construct(null, new \DateTime(), new \DateTime());

		$this->set('make', $make);
		$this->set('model', $model);
	}

}
