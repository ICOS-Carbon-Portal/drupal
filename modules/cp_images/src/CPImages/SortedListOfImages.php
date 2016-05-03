<?php

namespace Drupal\cp_images\CPImages;

use Drupal\cp_images\CPImages\ListOfImages;

class SortedListOfImages {
	
	private $list;
	
	function __construct() {
		$listOfImages = new ListOfImages();
		$this->list = $listOfImages->getListOfImages();
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