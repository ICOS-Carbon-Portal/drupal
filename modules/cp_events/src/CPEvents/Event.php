<?php

namespace Drupal\cp_events\CPEvents;

class Event {
	
	private $id;
	private $heading;
	private $text;
	private $picture_url;
	private $picture_title;
	private $link_url;
	private $link_title;
	private $from_date;
	private $to_date;
	private $news;
	private $historical;
	private $deprecated;
	private $created;
	private $changed;
	
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
	
	public function getPhoto() {
		return $this->photo;
	}
	
	public function setPhoto($photo) {
		$this->photo = $photo;
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
	
	public function getChanged() {
		return $this->changed;
	}
	
	public function setChanged($changed) {
		$this->changed = $changed;
	}
}