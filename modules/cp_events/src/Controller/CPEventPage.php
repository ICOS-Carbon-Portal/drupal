<?php

namespace Drupal\cp_events\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\cp_events\CPEvents\ListOfEvents;
use Drupal\Core\StreamWrapper\PublicStream;

class CPEventPage extends ControllerBase {
	
	public function content($event_id) {
	  	
	  	$listOfEvents = new ListOfEvents();
	  	$list = $listOfEvents->getListOfEvents();
	  	
	  	$date_format = 'Y-m-d';
	  	$settings = \Drupal::service('config.factory')->getEditable('cp_events.settings');
	  	
	  	if ($settings->get('settings.date_format') == 'day-month-year') { $date_format = 'd-m-Y'; }
	  	
	  	$has_event = 0;
	  	foreach ($list as $e) {
	  		if ($e->getId() == $event_id) {
	  		
	  			$has_event = 1;
	  			
	  			if ($e->getFromDate() != null && $e->getFromDate() != '') {
	  				$e->setFromDate(date($date_format, strtotime($e->getFromDate())));
	  				 
	  			} else {
	  				if ($e->getNews() == 0) {
	  					$e->setFromDate(date($date_format, $e->getCreated()));
	  				} else {
	  					$e->setFromDate(date($date_format, $e->getChanged()));
	  				}
	  			}
	  			
	  			if ($e->getToDate() != null && $e->getToDate() != '') {
	  				
	  				$now = time();
	  				$user = \Drupal::currentUser();
	  				if (strtotime($e->getToDate()) < $now) {
	  					if ($user->hasPermission('edit any cp_event content')) {
	  						$has_event = 1;
	  					} else {
	  						$has_event = 0;
	  					}
	  				}
	  				
	  				$e->setToDate(date($date_format, strtotime($e->getToDate())));
	  			
	  			}	  			
	  			
	  			$this->_formatPictureUri($e);
	  			$this->_formatLinkUri($e);
	  			$list_of_elements['event'] = $e;

	  			break;
	  		}
	  	}
	  	
	  	if ($has_event) {
		    return array(
		        '#theme' => 'selected_cp_event',
				'#elements' => $list_of_elements,
		    	'#attached' => array(
		    		'library' =>  array(
		    			'cp_events/style',
		    			'cp_events/script'
		    		),
		    	),
		    );

	  	} else {
		    	return array('#markup' => '');
		    }
	}
	
	function _formatPictureUri($event) {
		if ($event->getPictureUri() != null && $event->getPictureUri() != '') {
			$event->setPictureUri('/' . PublicStream::basePath() . '/' . str_replace('public://', '', $event->getPictureUri()));
	
			if ($event->getPictureTitle() != null && $event->getPictureTitle() != '') {
				$event->setPictureTitle($event->getPictureTitle());
			}
				
		} else {
			$event->setPictureUri('');
			$event->setPictureTitle('');
		}
	}
	
	function _formatLinkUri($event) {
		if ($event->getLinkUri() != null && $event->getLinkUri() != '') {	
			if ($event->getLinkTitle() == null || $event->getLinkTitle() == '') { 
				$event->setLinkTitle($event->getLinkUri());
			}	
		}
	}
}