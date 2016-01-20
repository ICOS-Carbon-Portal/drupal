<?php

namespace Drupal\cp_cmis\CMIS;

class Document {
	
	private $id;
	private $name;
	private $description;
	private $lastModifiedBy;
	private $lastModifiedDate;
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
	
	public function getLastModifiedBy() {
		return $this->lastModifiedBy;
	}
	
	public function setLastModifiedBy($lastModifiedBy) {
		$this->lastModifiedBy = $lastModifiedBy;
	}
	
	public function getLastModifiedDate() {
		return $this->lastModifiedDate;
	}
	
	public function setLastModifiedDate($lastModifiedDate) {
		$this->lastModifiedDate = $lastModifiedDate;
	}
}