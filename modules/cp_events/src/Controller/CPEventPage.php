<?php

namespace Drupal\cp_events\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\cp_events\CPEvents\ListOfEvents;
use Drupal\Core\StreamWrapper\PublicStream;

class CPEventPage extends ControllerBase {
	
	public function content($event_id) {
	  	
	  	$listOfEvents = new ListOfEvents();
	  	$list = $listOfEvents->getListOfEvents();
	  	
	  	$event = null;
	  	foreach ($list as $e) {
	  		if ($e->getId() == $event_id) {
	  			$event = $e;
	  		}
	  	}
	  	
	    return array(
	        '#markup' => $this->_build_html($event),
	    	'#attached' => array(
	    		'library' =>  array(
	    			'cp_events/style',
	    			'cp_events/script'
	    		),
	    	),
	    );
	}

	function _build_html($event) {
		
		$user = \Drupal::currentUser();	
		$edit = '';
		if ($user->hasPermission('edit any cp_event content')) {
			$edit = '<div class="edit_this"><a href="/node/' . $event->getId() . '/edit?destination=node/' . $event->getId() . '">Edit this</a></div>';
		}
		
		$date_format = 'Y-m-d';
		$settings = \Drupal::service('config.factory')->getEditable('cp_events.settings');
		if ($settings->get('settings.date_format') == 'day-month-year') { $date_format = 'd-m-Y'; }
  
	  	$output = '<div id="cp_events">';
	  	
	  	$output .= $edit;
	  
	  	$url = '/' . PublicStream::basePath() . '/';
	  
		$output .= '<div class="full_event">';
	  
		$from_date = '';
	  	if ($event->getFromDate() != null && $event->getFromDate() != '') {
	  		$from_date = date($date_format, strtotime($event->getFromDate()));
	  		
	  	} else {
	  		if ($event->getNews() == 0) {
	  			$from_date = date($date_format, $event->getCreated());
	  		} else {
	  			$from_date = date($date_format, $event->getChanged());
	  		}
	  	}
	  	
	  	$to_date = '';
	  	if ($event->getToDate() != null && $event->getToDate() != '') {
	  		$to_date = '<span class="date_separator">--</span><div class="to_date">' . date($date_format, strtotime($event->getToDate())) . '</div>';
	  	
	  	}
	  
	  	$output .= '<div class="from_date">' . $from_date . '</div>';
	  	$output .= $to_date;
	  	
	  	$output .= '<div class="heading">' . $event->getTitle() . '</div>';
	  
	  	if ($event->getPictureUri() != null && $event->getPictureUri() != '') {
	  		$picture_url = $url . str_replace('public://', '', $event->getPictureUri());
	  					
	  		$picture_title = '';
	  		if ($event->getPictureTitle() != null && $event->getPictureTitle() != '') { $picture_title = $event->getPictureTitle(); }
	  					
	  		$output .= '<div class="text"><img src="' . $picture_url . '" alt="' . $picture_title . '" title="' . $picture_title . '" />' . $event->getBody() . '</div>';
	  	
	  	} else {
	  		$output .= '<div class="text">' . $event->getBody() . '</div>';
	  		
	  	}
  
  		if ($event->getLinkUri() != null && $event->getLinkUri() != '') {
  			$link_title = $event->getLinkUri();
  			
  			if ($event->getLinkTitle() != null && $event->getLinkTitle() != '') { $link_title = $event->getLinkTitle(); }
  			
  			$output .= '<div class="link"><a href="' . $event->getLinkUri() . '">' . $link_title . '</a></div>';
  		}
  				
  		$output .= '</div>';
  		
  		$output .= '</div>';
  
  		return $output;
	}
}