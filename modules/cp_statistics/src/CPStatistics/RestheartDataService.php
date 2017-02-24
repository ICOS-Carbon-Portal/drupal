<?php

namespace Drupal\cp_statistics\CPStatistics;

use Drupal\cp_statistics\CPStatistics\IDataService;
use Drupal\cp_statistics\CPStatistics\Entry;
use Drupal\cp_statistics\CPStatistics\Page;
use Drupal\cp_statistics\CPStatistics\RelevantPages;

class RestheartDataService implements IDataService {

	private $service;
	
	public function __construct($service) {
		$this->service = $service;
	}
	
	public function getYears() {
		$list = array();
	
		$query = '/_aggrs/getYears';
		
		$contextSettings = ['http' => [ 'header' => 'Accept: application/json']];
		$context = stream_context_create($contextSettings);
		$result =json_decode(file_get_contents($this->service . $query, false, $context), true);
		
		foreach ($result['_embedded']['rh:result'] as $row) {
			$list['years'][] = $row['year'];
		}
		
		return $list;
	}
	
	public function getMonths($year) {
		$list = array();
		
		$query = '/_aggrs/getMonths?avars=%7B%22year%22%3A%22' . $year . '%22%7D';
		
		$contextSettings = ['http' => [ 'header' => 'Accept: application/json']];
		$context = stream_context_create($contextSettings);	
		$result =json_decode(file_get_contents($this->service . $query, false, $context), true);
		
		foreach ($result['_embedded']['rh:result'] as $row) {
			$list['months_in_' . $year][] = array('month' => $row['month']);
		}
		
		return $list;
	}
	
	public function getTotalVisits($year, $month) {
		return null;
	}
	
	public function getUniqueVisitors($year, $month) {
		$list = array();
		
		$rp = new RelevantPages();
		$pages = $rp->collectRelevantContent();
		
		$count = 0;
		
		foreach ($pages as $page) {
			$alias = $page->getPage();
			if ($page->getAlias()) { $alias = $page->getAlias(); }
				
			$count += $this->collectUniqueVisitorsPerPage($year, $month, $alias);
		}
		
		$list['unique_visits_in_' . $year . '_' . $month][] = array('unique_visits' => $count);
		
		return $list;
	}
	
	public function getUniqueVisitorsPerPage($year, $month, $number_of_pages) {
		$list = array();
		
		$rp = new RelevantPages();
		$pages = $rp->collectRelevantContent();
		
		foreach ($pages as $page) {
			$alias = $page->getPage();
			if ($page->getAlias()) { $alias = $page->getAlias(); }
				
			$page->setNumberOfVisits($this->collectUniqueVisitorsPerPage($year, $month, $alias));
		}
		
		usort($pages, array($this,'compare_desc'));
		
		if ($number_of_pages == 0) {
			$number_of_pages = count($pages) * 2;
		}
		
		foreach ($pages as $page) {
				
			if ($number_of_pages > 0 && $page->getNumberOfVisits()) {
					
				$path = $page->getPage();
				if ($page->getAlias()) {
					$path = $page->getAlias();
				}
		
				$list['pages'][] = array('page' => $path, 'unique_visits' => $page->getNumberOfVisits());
		
				$number_of_pages --;
			}
		}
		
		return $list;
	}
	
	public function getPages() {
		$list = array();
	
		$rp = new RelevantPages();
		$pages = $rp->collectRelevantContent();
	
		foreach ($pages as $page) {
	
			$path = $page->getPage();
			if ($page->getAlias()) {
				$path = $page->getAlias();
			}
	
			$list['pages'][] = $path;
		}
	
		return $list;
	}
	
	private function collectUniqueVisitorsPerPage($year, $month, $page) {
		$count = 0;
		
		$query = '/_aggrs/getUniqueVisitorsPerPage?avars=%7B%22year%22%3A%22' . $year . '%22%2C%22month%22%3A%22' . $month . '%22%2C%22page%22%3A%22' . $page . '%22%7D';
		
		$contextSettings = ['http' => [ 'header' => 'Accept: application/json']];
		$context = stream_context_create($contextSettings);
		$result =json_decode(file_get_contents($this->service . $query, false, $context), true);
		
		if (isset($result['_embedded'])) {
			foreach ($result['_embedded']['rh:result'] as $row) {
				$count += $row['count'];
			}
		}
		
		return $count;
	}
	
	private static function compare_asc($a, $b) {
		return $a->getNumberOfVisits() > $b->getNumberOfVisits() ? 1 : -1;
	}
	
	private static function compare_desc($a, $b) {
		return $a->getNumberOfVisits() < $b->getNumberOfVisits() ? 1 : -1;
	}
}
