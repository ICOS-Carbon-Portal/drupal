<?php

namespace Drupal\cp_statistics\Controller;

use Drupal\cp_statistics\CPStatistics\Entry;

use Drupal\Core\Controller\ControllerBase;

class CPStatisticsPage extends ControllerBase {
	
	public function showStatistics() {
		
		$list = $this->_collect_statistics_ip('2016', '12');
		
		$output = '<div>'.count($list).'</div>';
		
		return array('#markup' => $output);
	}

	function _collect_statistics_year() {
		$db = \Drupal::service('database');
	
		$result = $db->query('
				select distinct year
				from cp_statistics_visit'
			)->fetchAll();
	
		$list = array();
	
		if (! empty($result)) {
			foreach ($result as $row) {
				$list[] = $row->year;
			}
		}
	
		return $list;
	}
	
	function _collect_statistics_ip($year, $month) {
		$db = \Drupal::service('database');
		
		$result = $db->query('
				select distinct ip
				from cp_statistics_visit
				where year = :year
				and month = :month',
				
				array(':year' => $year, ':month' => $month)			
			)->fetchAll();
		
		$list = array();
		
		if (! empty($result)) {
			foreach ($result as $row) {
				$entry = new Entry();
				$entry->setIp($row->ip);
				$list[] = $entry;
			}
		}
		
		return $list;
	}
	
	function _collect_statistics_entries($id) {
		$db = \Drupal::service('database');
	
		$result = $db->query('
				select ip, page, referrer, browser, inlogged, timestamp, year, month, day, clock
				from cp_statistics_visit
				where id = :id',
	
				array(':id' => $id)
			)->fetchAll();
	
		$list = array();
	
		if (! empty($result)) {
			foreach ($result as $row) {
				$entry = new Entry();
				$entry->setIp($row->ip);
				$entry->setPage($row->page);
				$entry->setReferrer($row->referrer);
				$entry->setBrowser($row->browser);
				$entry->setInlogged($row->inlogged);
				$entry->setTimestamp($row->timestamp);
				$entry->setYear($row->year);
				$entry->setMonth($row->month);
				$entry->setDay($row->day);
				$entry->setClock($row->clock);
				$list[] = $entry;
			}
		}

		return $list;
	}
}