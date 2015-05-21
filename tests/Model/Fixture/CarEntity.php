<?php

namespace Zortje\MVC\Tests\Model\Fixture;

use Zortje\MVC\Model\Table\Entity\Entity;

/**
 * Class CarEntity
 *
 * @package Zortje\MVC\Tests\Model\Fixture
 */
class CarEntity extends Entity {

	protected static $columns = [
		'make'     => 'string',
		'model'    => 'string',
		'hp'       => 'integer',
		'released' => 'Date'
	];

	/**
	 * @param string    $make
	 * @param string    $model
	 * @param integer   $hp
	 * @param \DateTime $released
	 */
	public function __construct($make, $model, $hp, \DateTime $released) {
		parent::__construct(null, new \DateTime(), new \DateTime());

		$this->set('make', $make);
		$this->set('model', $model);
		$this->set('hp', $hp);
		$this->set('released', $released);
	}

}
