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
			$list['months'][] = array('month' => $row['month']);
		}
		
		return $list;
	}
	
	public function getUniqueVisitors($year, $month) {
		$list = array();
		
		$rp = new RelevantPages();
		$pages = $rp->collectRelevantContent();
		
		$listOfIp = array();
		
		foreach ($pages as $page) {
			$alias = $page->getPage();
			if ($page->getAlias()) { $alias = $page->getAlias(); }
			
			foreach ($this->collectUniqueVisitorsPerPage($year, $month, $alias) as $row) {
				if (! array_key_exists($row->getIp() ,$listOfIp)) {
					$listOfIp[$row->getIp()] = $row->getIp();
					$list['unique_visitors']['data'][] = array('ip' => $row->getIp());
				}	
			}
		}
		
		$list['unique_visitors']['total'] = array('number' => count($listOfIp));
		
		return $list;
	}
	
	public function getNumbersOfUniqueVisitors($year, $month, $numberOfPages) {
		$list = array();
		
		$rp = new RelevantPages();
		$pages = $rp->collectRelevantContent();
		
		$contextSettings = ['http' => [ 'header' => 'Accept: application/json']];
		$context = stream_context_create($contextSettings);
		
		foreach ($pages as $page) {
			$alias = $page->getPage();
			if ($page->getAlias()) { $alias = $page->getAlias(); }
			
			$query = '/_aggrs/getNumbersOfUniqueVisitors?avars=%7B%22year%22%3A%22' . $year . '%22%2C%22month%22%3A%22' . $month . '%22%2C%22page%22%3A%22' . $alias . '%22%7D';
			$result =json_decode(file_get_contents($this->service . $query, false, $context), true);
			
			$numberOfIp = 0;
			if (isset($result['_embedded'])) {
				foreach ($result['_embedded']['rh:result'] as $row) {
					$numberOfIp = $row['count'];
				}
			}
			
			$page->setNumberOfVisits($numberOfIp);
		}
		
		usort($pages, array($this,'compare_desc'));
		
		if ($numberOfPages == 0) {
			$numberOfPages = count($pages);
		}
		
		foreach ($pages as $page) {
				
			if ($numberOfPages > 0 && $page->getNumberOfVisits()) {
					
				$path = $page->getPage();
				if ($page->getAlias()) {
					$path = $page->getAlias();
				}
		
				$list['pages'][] = array('page' => $path, 'unique_visits' => $page->getNumberOfVisits());
		
				$numberOfPages --;
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
		$list = array();
		
		$numberOfPages = 1000;
		$pageNumber = 1;
		$totalPages = 0;
		
		$query = $this->getUniqueVisitorsPerPageQuery($year, $month, $page, $numberOfPages, $pageNumber);
		
		$contextSettings = ['http' => [ 'header' => 'Accept: application/json']];
		$context = stream_context_create($contextSettings);
		$result =json_decode(file_get_contents($this->service . $query, false, $context), true);
		$list = $this->getUniqueVisitorsPerPageData($list, $result);
		
		if (isset($result['_total_pages']) && $result['_total_pages'] > 1) {
		
			$totalPages = $result['_total_pages'];
		
			while ($pageNumber < $totalPages) {
				$pageNumber ++;
					
				$query = $this->getUniqueVisitorsPerPageQuery($year, $month, $page, $numberOfPages, $pageNumber);
				$result =json_decode(file_get_contents($this->service . $query, false, $context), true);
				$list = array_merge($list, $this->getUniqueVisitorsPerPageData($list, $result));
			}
		}
		
		return $list;
	}
	
	private function getUniqueVisitorsPerPageQuery($year, $month, $page, $numberOfPages, $pageNumber) {
		$query = '/_aggrs/getUniqueVisitors?avars=%7B%22year%22%3A%22' . $year . '%22%2C%22month%22%3A%22' . $month . '%22%2C%22page%22%3A%22' . $page . '%22%7D&pagesize=' . $numberOfPages . '&page=' . $pageNumber;
		return $query;
	}
	
	private function getUniqueVisitorsPerPageData($list, $data) {
		
		if($data  && isset($data['_embedded'])) {
			foreach ($data['_embedded']['rh:result'] as $row) {
				$entry = new Entry();
				$entry->setIp($row['ip']);
				$list[] = $entry;
			}
		}
		
		return $list;
	}
	
	private static function compare_asc($a, $b) {
		return $a->getNumberOfVisits() > $b->getNumberOfVisits() ? 1 : -1;
	}
	
	private static function compare_desc($a, $b) {
		return $a->getNumberOfVisits() < $b->getNumberOfVisits() ? 1 : -1;
	}
}
