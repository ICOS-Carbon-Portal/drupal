<?php

namespace Drupal\cp_statistics\CPStatistics;

use Drupal\cp_statistics\CPStatistics\IDataService;
use Drupal\cp_statistics\CPStatistics\Entry;
use Drupal\cp_statistics\CPStatistics\Page;
use Drupal\cp_statistics\CPStatistics\RelevantPages;

class InternalDataService implements IDataService {

	public function getYears() {
		$list = array();

		$result = db_query('select distinct year from cp_statistics_visit order by year desc')->fetchAll();
		foreach ($result as $row) {
			$list['years'][] = $row->year;
		}

		return $list;
	}
	
	public function getMonths($year) {
		$list = array();
		
		$result = db_query('
				select distinct month 
				from cp_statistics_visit 
				where year = :year
				',
				
				array(':year' => $year)
		)->fetchAll();
		
		foreach ($result as $row) {
			$list['months'][] = array('month' => $row->month);
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
			
			$result = db_query('
					select distinct ip
					from cp_statistics_visit
					where year = :year
					and month = :month
					and (
					page in ( :page )
					or page in ( :alias )
					)
					',
					
					array(':year' => $year, ':month' => $month, ':page' => $page->getPage(), ':alias' => $alias)
			)->fetchAll();
			
			foreach ($result as $row) {
				if (! array_key_exists($row->ip ,$listOfIp)) {
					$listOfIp[$row->ip] = $row->ip;
					$list['unique_visitors']['data'][] = array('ip' => $row->ip);
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
		
		foreach ($pages as $page) {
			$alias = $page->getPage();
			if ($page->getAlias()) { $alias = $page->getAlias(); }
			
			$result = db_query('
					select page, count(distinct ip) as number_of_ip
					from cp_statistics_visit
					where year = :year
					and month = :month
					and (
					page in ( :page ) 
					or page in ( :alias )
					)
					group by page
					',
						
					array(':year' => $year, ':month' => $month, ':page' => $page->getPage(), ':alias' => $alias)
			)->fetchAll();
			
			$numberOfIp = 0;
			foreach ($result as $row) {
				if ($row->number_of_ip) {
					$numberOfIp += $row->number_of_ip;
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
	
	private static function compare_asc($a, $b) {
		return $a->getNumberOfVisits() > $b->getNumberOfVisits() ? 1 : -1;
	}
	
	private static function compare_desc($a, $b) {
		return $a->getNumberOfVisits() < $b->getNumberOfVisits() ? 1 : -1;
	}
}
