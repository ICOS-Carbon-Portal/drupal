<?php

namespace Drupal\cp_events\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\cp_events\CPEvents\ListOfEvents;
use Drupal\Core\StreamWrapper\PublicStream;

class CPEventPageController extends ControllerBase {
	
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
  
	  	$output = '<div id="cp_events">';
	  
	  	$url = $GLOBALS['base_url'] . '/' . PublicStream::basePath() . '/';
	  
	  	
		$output .= '<div class="full-event">';
	  
	  	if ($event->getFromDate() != null || $event->getFromDate() != '') {
	  		$output .= '<div class="from_date">' . $event->getFromDate() . '</div>';
	  	}
	  
	  	$output .= '<div class="heading">' . $event->getHeading() . '</div>';
	  
	  	if ($event->getPictureUrl() != null || $event->getPictureUrl() != '') {
	  		$picture_url = $url . str_replace('public://', '', $event->getPictureUrl());
	  					
	  		$picture_title = '';
	  		if ($event->getPictureTitle() != null || $event->getPictureTitle() != '') { $picture_title = $event->getPictureTitle(); }
	  					
	  		$output .= '<div class="text"><img src="' . $picture_url . '" alt="' . $picture_title . '" title="' . $picture_title . '" />' . $event->getText() . '</div>';
	  	
	  	} else {
	  		$output .= '<div class="text">' . $event->getText() . '</div>';
	  		
	  	}
  
  		if ($event->getLinkUrl() != null || $event->getLinkUrl() != '') {
  			$link_title = $event->getLinkUrl();
  			
  			if ($event->getLinkTitle() != null || $event->getLinkTitle() != '') { $link_title = $event->getLinkTitle(); }
  			
  			$output .= '<div class="link"><a href="' . $event->getLinkUrl() . '">' . $link_title . '</a></div>';
  		}
  				
  		$output .= '</div>';
  		
  		$output .= '</div>';
  
  		return $output;
	}
}