<?php

namespace Drupal\cp_blogs\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\cp_blogs\CPBlogs\SortedListOfBlogs;
use Drupal\Core\StreamWrapper\PublicStream;

class ListOfCPBlogs extends ControllerBase {
	
	public function content($blog_category) {
	  	
	  	$listOfBlogs = new SortedListOfBlogs();
		$list = $listOfBlogs->getListLatestFirst();
	  	
		$date_format = 'Y-m-d';
		$settings = \Drupal::service('config.factory')->getEditable('cp_blogs.settings');
		if ($settings->get('settings.date_format') == 'day-month-year') { $date_format = 'd-m-Y'; }
		
		$list_of_elements = array();
		$list_of_elements['page_title'] = $blog_category;
		
		foreach ($list as $b) {
			if ($b->getCategory() == $blog_category) {
		
				$date = date($date_format, $b->getChanged());
				$dateTime = new \DateTime($date);
				$year = $dateTime->format('Y');
		
				$b->setChanged($date);
				$list_of_elements['year'][$year][] = $b;
		
			}
		}
		
	    return array(
	        '#theme' => 'list_of_cp_blogs',
			'#elements' => $list_of_elements,
	    	'#attached' => array(
	    		'library' =>  array(
	    			'cp_blogs/style',
	    			'cp_blogs/script'
	    		),
	    	),
	    );
	}
}