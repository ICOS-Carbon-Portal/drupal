<?php

namespace Drupal\ns_stationdata\NSStationdata;

class Stationdata {
	
	private $id;
	private $title;
	private $body;
  	private $country;
  	private $latitude;
  	private $longitude;
  	private $measurementHeight;
  	private $vegetationType;
  	private $variables;
  	private $sensors;
  	private $co2Measurements;    
  	private $stationPi;
  	private $years;
	
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
	
	public function getCountry() {
		return $this->country;
	}
	
	public function setCountry($country) {
		$this->country = $country;
	}
	
	public function getLatitude() {
		return $this->latitude;
	}
	
	public function set($latitude) {
		$this->latitude = $latitude;
	}
	
	public function getLongitude() {
		return $this->longitude;
	}
	
	public function setLongitude($longitude) {
		$this->longitude = $longitude;
	}
	
	public function getMeasurementHeight() {
		return $this->measurementHeight;
	}
	
	public function setMeasurementHeight($measurementHeight) {
		$this->measurementHeight = $measurementHeight;
	}
	
	public function getVegetationType() {
		return $this->vegetationType;
	}
	
	public function setVegetationType($vegetationType) {
		$this->vegetationType = $vegetationType;
	}
	
	public function getVariables() {
		return $this->variables;
	}
	
	public function setVariables($variables) {
		$this->variables = $variables;
	}
	
	public function getSensors() {
		return $this->sensors;
	}
	
	public function setSensors($sensors) {
		$this->sensors = $sensors;
	}
	
	public function getCo2Measurements() {
		return $this->co2Measurements;
	}
	
	public function setCo2Measurements($co2Measurements) {
		$this->co2Measurements = $co2Measurements;
	}
	
	public function getStationPi() {
		return $this->stationPi;
	}
	
	public function setStationPi($stationPi) {
		$this->stationPi = $stationPi;
	}
	
	public function getYears() {
		return $this->years;
	}
	
	public function setYears($years) {
		$this->years = $years;
	}
}