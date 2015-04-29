<?php

namespace Zortje\MVC\Model;

/**
 * Class Entity
 *
 * @package Zortje\MVC\Model
 */
class Entity {

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
	 * @param int       $id
	 * @param \DateTime $modified
	 * @param \DateTime $created
	 */
	public function __construct($id, $modified, $created) {
		$this->id       = $id;
		$this->modified = $modified;
		$this->created  = $created;
	}

}
