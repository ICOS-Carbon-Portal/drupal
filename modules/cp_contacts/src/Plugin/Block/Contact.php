<?php

namespace Drupal\cp_contacts\Plugin\Block;

class Contact {
	
	private $id;
	private $name;
	private $email;
	private $phone;
	private $photo;
	private $organization;
	private $title;
	private $index;
	
	function __construct() {
	
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getEmail() {
		return $this->email;
	}
	
	public function setEmail($email) {
		$this->email = $email;
	}
	
	public function getPhone() {
		return $this->phone;
	}
	
	public function setPhone($phone) {
		$this->phone = $phone;
	}
	
	public function getPhoto() {
		return $this->photo;
	}
	
	public function setPhoto($photo) {
		$this->photo = $photo;
	}
	
	public function getOrganization() {
		return $this->organization;
	}
	
	public function setOrganization($organization) {
		$this->organization = $organization;
	}

	public function getTitle() {
		return $this->title;
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function getIndex() {
		return $this->index;
	}
	
	public function setIndex($index) {
		$this->index = $index;
	}
}