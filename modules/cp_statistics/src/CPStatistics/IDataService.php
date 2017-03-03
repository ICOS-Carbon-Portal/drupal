<?php

namespace Drupal\cp_statistics\CPStatistics;

interface IDataService {

	public function getYears();
	
	public function getMonths($year);

	public function getUniqueVisitors($year, $month);
	
	public function getNumbersOfUniqueVisitors($year, $month, $numberOfPages);
	
	public function getPages();
}
