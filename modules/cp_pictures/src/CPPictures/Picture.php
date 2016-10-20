<?php

namespace Drupal\cp_pictures\CPPictures;

class Picture {
	
	private $id;
	private $title;
	private $body;
	private $created;
	private $changed;
	private $picture_uri;
	private $picture_name;
	private $picture_description;
	private $picture_width;
	private $picture_height;
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
	
	public function getBody() {
		return $this->body;
	}
	
	public function setBody($body) {
		$this->body = $body;
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
	
	public function getPictureUri() {
		return $this->picture_uri;
	}
	
	public function setPictureUri($picture_uri) {
		$this->picture_uri = $picture_uri;
	}
	
	public function getPictureName() {
		return $this->picture_name;
	}
	
	public function setPictureName($picture_name) {
		$this->picture_name = $picture_name;
	}
	
	public function getPictureDescription() {
		return $this->picture_description;
	}
	
	public function setPictureDescription($picture_description) {
		$this->picture_description = $picture_description;
	}
	
	public function getPictureWidth() {
		return $this->picture_width;
	}
	
	public function setPictureWidth($picture_width) {
		$this->picture_width = $picture_width;
	}
	
	public function getPictureHeight() {
		return $this->picture_height;
	}
	
	public function setPictureHeight($picture_height) {
		$this->picture_height = $picture_height;
	}
	
	public function getCategory() {
		return $this->category;
	}
	
	public function setCategory($category) {
		$this->category = $category;
	}
}