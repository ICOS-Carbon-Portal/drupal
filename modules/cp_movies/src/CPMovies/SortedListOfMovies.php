<?php

namespace Drupal\cp_movies\CPMovies;

use Drupal\cp_movies\CPMovies\ListOfMovies;

class SortedListOfMovies {
	
	private $list;
	
	function __construct() {
		$listOfMovies = new ListOfMovies();
		$this->list = $listOfMovies->getListOfMovies();
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