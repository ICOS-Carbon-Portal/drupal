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
			$list['months_in_' . $year][] = array('month' => $row->month);
		}
		
		return $list;
	}
	
	public function getTotalVisits($year, $month) {
		$list = array();
		
		$result = db_query('
				select count(ip) as total_ip 
				from cp_statistics_visit 
				where year = :year
				and month = :month
				',
			
				array(':year' => $year, ':month' => $month)
		);
		foreach ($result as $row) {
			
			$list['total_visits_in_'. $year . '_' . $month][] = array('total_visits' => $row->total_ip);
		}
		
		return $list;
	}
	
	public function getUniqueVisitors($year, $month) {
		$list = array();
		
		$rp = new RelevantPages();
		$pages = $rp->collectRelevantContent();
		
		$list_of_ip = array();
		
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
				if (! array_key_exists($row->ip ,$list_of_ip)) {
					$list_of_ip[$row->ip] = $row->ip;
				}
			}
		}
		
		$list['unique_visits_in_' . $year . '_' . $month][] = array('unique_visits' => count($list_of_ip));
		
		return $list;
	}
	
	public function getUniqueVisitorsPerPage($year, $month, $number_of_pages) {
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
			
			$number_of_ip = 0;
			foreach ($result as $row) {
				if ($row->number_of_ip) {
					$number_of_ip += $row->number_of_ip;
				}
			}
			
			$page->setNumberOfVisits($number_of_ip);
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
	
	private static function compare_asc($a, $b) {
		return $a->getNumberOfVisits() > $b->getNumberOfVisits() ? 1 : -1;
	}
	
	private static function compare_desc($a, $b) {
		return $a->getNumberOfVisits() < $b->getNumberOfVisits() ? 1 : -1;
	}
}
