<?php

namespace Drupal\cp_documents\CPDocuments;

use Drupal\cp_documents\CPDocuments\ListOfDocuments;

class SortedListOfDocuments {
	
	private $list;
	
	function __construct() {
		$listOfDocuments = new ListOfDocuments();
		$this->list = $listOfDocuments->getListOfDocuments();
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