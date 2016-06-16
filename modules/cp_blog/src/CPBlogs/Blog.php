<?php

namespace Drupal\cp_blogs\CPBlogs;

class Blog {
	
	private $id;
	private $title;
	private $created;
	private $changed;
	private $text;
	private $picture_url;
	private $picture_title;
	private $link_url;
	private $link_title;
	private $category;
	private $historical;
	private $deprecated;
	
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
	
	public function getText() {
		return $this->text;
	}
	
	public function setText($text) {
		$this->text = $text;
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

	public function getLinkUrl() {
		return $this->link_url;
	}
	
	public function setLinkUrl($link_url) {
		$this->link_url = $link_url;
	}
	
	public function getLinkTitle() {
		return $this->link_title;
	}
	
	public function setLinkTitle($link_title) {
		$this->link_title = $link_title;
	}

	public function getCategory() {
		return $this->category;
	}
	
	public function setCategory($category) {
		$this->category = $category;
	}
	
	public function getHistorical() {
		return $this->historical;
	}
	
	public function setHistorical($historical) {
		$this->historical = $historical;
	}
	
	public function getDeprecated() {
		return $this->deprecated;
	}
	
	public function setDeprecated($deprecated) {
		$this->deprecated = $deprecated;
	}
}