<?php

namespace Drupal\cp_files\CPFiles;

class File {
	
	private $id;
	private $uri;
	private $name;
	private $mime;
	private $description;	
	

	function __construct() {
	
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getUri() {
		return $this->uri;
	}
	
	public function setUri($uri) {
		$this->uri = $uri;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getMime() {
		return $this->mime;
	}
	
	public function setMime($mime) {
		$this->mime = $mime;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
}