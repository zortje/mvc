<?php

namespace Zortje\MVC\Model;

/**
 * Class EntityProperty
 *
 * @package Zortje\MVC\Model
 */
class EntityProperty {

	/**
	 * @var string Entity property type
	 */
	protected $type;

	/**
	 * Format value according to entity property type
	 *
	 * @todo rename to `formatValueForEntity($value)`
	 *
	 * @param mixed $value Value
	 *
	 * @return mixed Value
	 */
	public function formatValue($value) {
		switch ($this->type) {
			case 'string':
				$value = "$value";
				break;

			case 'integer':
				$value = (int) $value;
				break;

			case 'float':
				$value = (float) $value;
				break;

			case 'DateTime':
				$value = new \DateTime($value);
				break;
		}

		return $value;
	}

	/**
	 * @param string $type
	 */
	public function __construct($type) {
		$this->type = $type;
	}

}
