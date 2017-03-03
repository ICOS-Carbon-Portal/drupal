<?php

namespace Drupal\cp_statistics\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\cp_statistics\CPStatistics\InternalDataService;
use Drupal\cp_statistics\CPStatistics\RestheartDataService;
use Symfony\Component\HttpFoundation\Response;

class CPStatisticsAjaxResponse extends ControllerBase {

	public function getData($service, $year, $month) {
		$response = new Response();
		$response->headers->set('Content-Type', 'application/json');
		
		$message;
		
		switch ($service) {
			
			case 'years':
				$message = $this->getYears();
				break;
			
			case 'months':
				$message = $this->getMonths($year);
				break;
					
			case 'uniquevisitors':
				$message = $this->getUniqueVisitors($year, $month);
				break;
					
			case 'numbersofuniquevisitorsper10page':
				$message = $this->getNumbersOfUniqueVisitors($year, $month, 10);
				break;
				
			case 'numbersofuniquevisitorsper20page':
				$message = $this->getNumbersOfUniqueVisitors($year, $month, 20);
				break;
				
			case 'numbersofuniquevisitorsperallpage':
				$message = $this->getNumbersOfUniqueVisitors($year, $month, 0);
				break;
				
			case 'pages':
				$message = $this->getPages();
				break;
				
			default:
				$message = array('message' => 'none');
		}
		
		$response->setContent(json_encode($message));
		
		return $response;
	}

	private function getYears() {
		return $this->getService()->getYears();
	}
	
	private function getMonths($year) {
		return $this->getService()->getMonths($year);
	}
	
	private function getUniqueVisitors($year, $month) {
		return $this->getService()->getUniqueVisitors($year, $month);
	}
	
	private function getNumbersOfUniqueVisitors($year, $month, $numberOfPages) {
		return $this->getService()->getNumbersOfUniqueVisitors($year, $month, $numberOfPages);
	}
	
	private function getPages() {
		return $this->getService()->getPages();
	}
	
	private function getService() {
		$service = '';
		
		$settings = \Drupal::config('cp_statistics.settings');
		
		if ($settings->get('settings.internal_or_restheart') == 'internal') {
			$service = new InternalDataService();
			
		} else if ($settings->get('settings.internal_or_restheart') == 'restheart'
				&& $settings->get('settings.restheart_get_path') != '') {
					
			$service = new RestheartDataService($settings->get('settings.restheart_get_path'));
		}
		
		return $service;
	}
}

