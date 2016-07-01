<?php

namespace Drupal\cp_pictures\CPPictures;

use Drupal\cp_pictures\CPPictures\ListOfPictures;

class SortedListOfPictures {
	
	private $list;
	
	function __construct() {	
		$listOfPictures = new ListOfPictures();
		$this->list = $listOfPictures->getListOfPictures();
	}
	
	function getListLatestLast() {
		usort($this->list, array($this,'_compare_desc'));
		return $this->list;
	}
	
	function getListLatestFirst() {
		usort($this->list, array($this,'_compare_asc'));
		return $this->list;
	}
	
	static function _compare_desc($a, $b) {
		return $a->getCreated() > $b->getCreated() ? 1 : -1;	
	}
	
	static function _compare_asc($a, $b) {
		return $a->getCreated() < $b->getCreated() ? 1 : -1;
	}
}