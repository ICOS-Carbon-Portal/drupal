<?php

namespace Drupal\ns_stationdata\NSStationdata;

use Drupal\ns_stationdata\NSStationdata\ListOfStationdata;

class SortedListOfStationdata {
	
	private $list;
	
	function __construct() {
		$listOfStationdata = new ListOfStationdata();
		$this->list = $listOfStationdata->getListOfData();		
	}

	function getListAsc() {
		usort($this->list, array($this,'_compare_asc'));
		return $this->list;
	}
	
	function getListDesc() {
		usort($this->list, array($this,'_compare_desc'));
		return $this->list;
	}
	
	static function _compare_asc($a, $b) {
		return $a->getTitle() < $b->getTitle() ? 1 : -1;
	}
	
	static function _compare_desc($a, $b) {
		return $a->getTitle() > $b->getTitle() ? 1 : -1;	
	}
}