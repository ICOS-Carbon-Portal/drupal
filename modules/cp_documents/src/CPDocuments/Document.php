<?php

namespace Drupal\cp_documents\CPDocuments;

class Document {
	
	private $id;
	private $title;
	private $created;
	private $changed;
	private $file_url;
	private $file_description;
	private $picture_url;
	private $picture_title;
	private $picture_width;
	private $picture_height;	
	private $category_key;
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
	
	public function getFileUrl() {
		return $this->file_url;
	}
	
	public function setFileUrl($file_url) {
		$this->file_url = $file_url;
	}
	
	public function getFileDescription() {
		return $this->file_description;
	}
	
	public function setFileDescription($file_description) {
		$this->file_description = $file_description;
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
	
	public function getCategoryKey() {
		return $this->category_key;
	}
	
	public function setCategoryKey($category_key) {
		$this->category_key = $category_key;
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