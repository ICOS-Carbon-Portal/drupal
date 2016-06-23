<?php

namespace Drupal\cp_media\CPMedia;

use Drupal\cp_media\CPMedia\ListOfMedia;

class SortedListOfMedia {
	
	private $list;
	
	function __construct() {
		$listOfMedia = new ListOfMedia();
		$this->list = $listOfMedia->getListOfMedia();
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