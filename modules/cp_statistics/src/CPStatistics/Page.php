<?php

namespace Drupal\cp_statistics\CPStatistics;

class Page {

	private $id;
	private $page;
	private $alias;
	private $numberOfVisits;

	function __construct() {
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getPage() {
		return $this->page;
	}
	
	public function setPage($page) {
		$this->page = $page;
	}
	
	public function getAlias() {
		return $this->alias;
	}
	
	public function setAlias($alias) {
		$this->alias = $alias;
	}
	
	public function getNumberOfVisits() {
		return $this->numberOfVisits;
	}
	
	public function setNumberOfVisits($numberOfVisits) {
		$this->numberOfVisits = $numberOfVisits;
	}
}