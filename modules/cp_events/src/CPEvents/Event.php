<?php

namespace Drupal\cp_events\CPEvents;

class Event {
	
	private $id;
	private $title;
	private $created;
	private $changed;
	private $text;
	private $picture_uri;
	private $picture_title;
	private $link_uri;
	private $link_title;
	private $from_date;
	private $to_date;
	private $news;
	private $historical;
	
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
	
	public function getLinkUri() {
		return $this->link_uri;
	}
	
	public function setLinkUri($link_uri) {
		$this->link_uri = $link_uri;
	}
	
	public function getLinkTitle() {
		return $this->link_title;
	}
	
	public function setLinkTitle($link_title) {
		$this->link_title = $link_title;
	}

	public function getFromDate() {
		return $this->from_date;
	}
	
	public function setFromDate($from_date) {
		$this->from_date = $from_date;
	}
	
	public function getToDate() {
		return $this->to_date;
	}
	
	public function setToDate($to_date) {
		$this->to_date = $to_date;
	}
	
	public function getNews() {
		return $this->news;
	}
	
	public function setNews($news) {
		$this->news = $news;
	}
	
	public function getHistorical() {
		return $this->historical;
	}
	
	public function setHistorical($historical) {
		$this->historical = $historical;
	}
}