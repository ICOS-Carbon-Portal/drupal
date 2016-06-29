<?php

namespace Drupal\cp_files\CPFiles;

use Drupal\cp_files\CPFiles\ListOfFiles;

class SortedListOfFiles {
	
	private $list;
	
	function __construct() {
		$listOfFiles = new ListOfFiles();
		$this->list = $listOfFiles->getListOfFiles();
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