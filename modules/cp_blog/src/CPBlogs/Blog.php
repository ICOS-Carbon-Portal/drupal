<?php

namespace Drupal\cp_blogs\CPBlogs;

class Blog {
	
	private $id;
	private $title;
	private $created;
	private $changed;
	private $body;
	private $picture_uri;
	private $picture_title;
	private $category;
	
	function __construct() {
	
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function getCreated() {
		return $this->created;
	}
	
	public function setCreated($created) {
		$this->created = $created;
	}
	
	public function getChanged() {
		return $this->changed;
	}
	
	public function setChanged($changed) {
		$this->changed = $changed;
	}
	
	public function getBody() {
		return $this->body;
	}
	
	public function setBody($body) {
		$this->body = $body;
	}
	
	public function getPictureUri() {
		return $this->picture_uri;
	}
	
	public function setPictureUri($picture_uri) {
		$this->picture_uri = $picture_uri;
	}
	
	public function getPictureTitle() {
		return $this->picture_title;
	}
	
	public function setPictureTitle($picture_title) {
		$this->picture_title = $picture_title;
	}

	public function getCategory() {
		return $this->category;
	}
	
	public function setCategory($category) {
		$this->category = $category;
	}
}