<?php

namespace Zortje\MVC\Model;

/**
 * Class Entity
 *
 * @package Zortje\MVC\Model
 */
abstract class Entity {

	/**
	 * @var int Entity ID
	 */
	protected $id;

	/**
	 * @var \DateTime Entity modified
	 */
	protected $modified;

	/**
	 * @var \DateTime Entity created
	 */
	protected $created;

	/**
	 * Set entity ID
	 *
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * Get entity ID
	 *
	 * @return int Entity ID
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set entity modified date
	 *
	 * @param \DateTime Entity modified date
	 */
	public function setModified($modified) {
		$this->modified = $modified;
	}

	/**
	 * Get entity modified date
	 *
	 * @return \DateTime Entity modified date
	 */
	public function getModified() {
		return $this->modified;
	}

	/**
	 * Set entity created date
	 *
	 * @param \DateTime Entity created date
	 */
	public function setCreated($created) {
		$this->created = $created;
	}

	/**
	 * Get entity created date
	 *
	 * @return \DateTime Entity created date
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * Return table structur for saving
	 * Example: `['table_field_name' => $this->fieldName]`
	 *
	 * @return array
	 */
	abstract protected function toArray();

	/**
	 * @param int       $id
	 * @param \DateTime $modified
	 * @param \DateTime $created
	 */
	public function __construct($id, \DateTime $modified, \DateTime $created) {
		$this->id       = $id;
		$this->modified = $modified;
		$this->created  = $created;
	}

}
