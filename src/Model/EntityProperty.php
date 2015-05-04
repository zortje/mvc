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
	 * @param mixed $value Value
	 *
	 * @return mixed Value
	 */
	public function formatValueForEntity($value) {
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

			case 'Date':
			case 'DateTime':
				$value = new \DateTime($value);
				break;
		}

		return $value;
	}

	/**
	 * Format value for insertion into the database
	 *
	 * @param mixed $value Value
	 *
	 * @return mixed Value
	 */
	public function formatValueForDatabase($value) {
		switch ($this->type) {
			case 'Date':
				/**
				 * @var \DateTime $value
				 */
				$value = $value->format('Y-m-d');
				break;

			case 'DateTime':
				/**
				 * @var \DateTime $value
				 */
				$value = $value->format('Y-m-d H:i:s');
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
