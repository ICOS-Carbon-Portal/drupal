<?php

namespace Drupal\cp_statistics\CPStatistics;

interface IDataService {

	public function getYears();
	
	public function getMonths($year);
	
	public function getTotalVisits($year, $month);

	public function getUniqueVisitors($year, $month);
	
	public function getUniqueVisitorsPerPage($year, $month, $number_of_pages);
	
	public function getPages();
}
