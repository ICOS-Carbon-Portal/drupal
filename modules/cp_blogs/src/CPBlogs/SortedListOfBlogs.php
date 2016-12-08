<?php

namespace Drupal\cp_blogs\CPBlogs;

use Drupal\cp_blogs\CPBlogs\ListOfBlogs;

class SortedListOfBlogs {
	
	private $list;
	
	function __construct() {
		$listOfBlogs = new ListOfBlogs();
		$this->list = $listOfBlogs->getListOfBlogs();
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