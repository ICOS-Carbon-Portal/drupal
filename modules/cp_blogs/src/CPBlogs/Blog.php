<?php

namespace Drupal\cp_blogs\CPBlogs;

class Blog {
	
	private $id;
	private $heading;
	private $picture_url;
	private $picture_title;
	private $text;
	private $deprecated;
	private $created;

	function __construct() {
	
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getHeading() {
		return $this->heading;
	}
	
	public function setHeading($heading) {
		$this->heading = $heading;
	}
	
	public function getPictureUrl() {
		return $this->picture_url;
	}
	
	public function setPictureUrl($picture_url) {
		$this->picture_url = $picture_url;
	}
	
	public function getPictureTitle() {
		return $this->picture_title;
	}
	
	public function setPictureTitle($picture_title) {
		$this->picture_title = $picture_title;
	}
	
	public function getText() {
		return $this->text;
	}
	
	public function setText($text) {
		$this->text = $text;
	}
	
	public function getDeprecated() {
		return $this->deprecated;
	}
	
	public function setDeprecated($deprecated) {
		$this->deprecated = $deprecated;
	}
	
	public function getCreated() {
		return $this->created;
	}
	
	public function setCreated($created) {
		$this->created = $created;
	}
}