<?php

namespace Drupal\cp_statistics\CPStatistics;

class Entry {

	private $id;
	private $ip;
	private $page;
	private $referrer;
	private $browser;
	private $inlogged;
	private $timestamp;
	private $year;
	private $month;
	private $day;
	private $clock;
	private $countryCode;
	private $lat;
	private $lon;

	function __construct() {
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getIp() {
		return $this->ip;
	}
	
	public function setIP($ip) {
		$this->ip = $ip;
	}
	
	public function getPage() {
		return $this->page;
	}
	
	public function setPage($page) {
		$this->page = $page;
	}
	
	public function getReferrer() {
		return $this->referrer;
	}
	
	public function setReferrer($referrer) {
		$this->referrer = $referrer;
	}
	
	public function getBrowser() {
		return $this->browser;
	}
	
	public function setBrowser($browser) {
		$this->browser = $browser;
	}
	
	public function getInlogged() {
		return $this->inlogged;
	}
	
	public function setInlogged($inlogged) {
		$this->inlogged = $inlogged;
	}
	
	public function getTimestamp() {
		return $this->timestamp;
	}
	
	public function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;
	}
	
	public function getYear() {
		return $this->year;
	}
	
	public function setYear($year) {
		$this->year = $year;
	}
	
	public function getMonth() {
		return $this->month;
	}
	
	public function setMonth($month) {
		$this->month = $month;
	}
	
	public function getDay() {
		return $this->day;
	}
	
	public function setDay($day) {
		$this->day = $day;
	}
	
	public function getClock() {
		return $this->clock;
	}
	
	public function setClock($clock) {
		$this->clock = $clock;
	}
	
	public function getCountryCode() {
		return $this->countryCode;
	}
	
	public function setCountryCode($countryCode) {
		$this->countryCode = $countryCode;
	}
	
	public function getLat() {
		return $this->lat;
	}
	
	public function setLat($lat) {
		$this->lat = $lat;
	}
	
	public function getLon() {
		return $this->lon;
	}
	
	public function setLon($lon) {
		$this->lon = $lon;
	}
}