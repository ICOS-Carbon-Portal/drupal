<?php

namespace Drupal\cp_events\CPEvents;

use Drupal\cp_events\CPEvents\ListOfEvents;

class SortedListOfEvents {
	
	private $list;
	
	function __construct() {
		$listOfEvents = new ListOfEvents();
		$this->list = $listOfEvents->getListOfEvents();
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
	
		$comp_a = $a->getCreated();
		if (($a->getFromDate() != null || $a->getFromDate() != '')) { $comp_a = strtotime($a->getFromDate()); }
	
		$comp_b = $b->getCreated();
		if (($b->getFromDate() != null || $b->getFromDate() != '')) { $comp_b = strtotime($b->getFromDate()); }
	
		if ($comp_a != $comp_b) {
			return $comp_a > $comp_b ? 1 : -1;
			
		} else {
			return $a->getCreated() > $b->getCreated() ? 1 : -1;
		}
	}
	
	static function _compare_asc($a, $b) {
	
		$comp_a = $a->getChanged();
		if (($a->getFromDate() != null || $a->getFromDate() != '')) { $comp_a = strtotime($a->getFromDate()); }
	
		$comp_b = $b->getChanged();
		if (($b->getFromDate() != null || $b->getFromDate() != '')) { $comp_b = strtotime($b->getFromDate()); }
	
		if ($comp_a != $comp_b) {
			return $comp_a < $comp_b ? 1 : -1;
			
		} else {
			$a->getChanged() < $b->getChanged() ? 1 : -1;
		}
	}
}