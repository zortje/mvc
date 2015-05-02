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
		'model' => 'string',
		'hp'    => 'integer'
	];

	/**
	 * @param string  $make
	 * @param string  $model
	 * @param integer $hp
	 */
	public function __construct($make, $model, $hp) {
		parent::__construct(null, new \DateTime(), new \DateTime());

		$this->set('make', $make);
		$this->set('model', $model);
		$this->set('hp', $hp);
	}

}
