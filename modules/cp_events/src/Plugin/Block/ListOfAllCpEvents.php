<?php

namespace Drupal\cp_events\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_events\CPEvents\ListOfCPEvents;
use Drupal\Core\StreamWrapper\PublicStream;

/**
 * @Block(
 *   id = "list_of_all_cp_events",
 *   admin_label = @Translation("List of all CP events"),
 * )
 */
class ListOfAllCpEvents extends BlockBase {
	
	function build() {
		$listOfCPEvents = new ListOfCPEvents();
		$list = $listOfCPEvents->getListOfEvents();
	
		return array(
			'#markup' => $this->_build_html($list),
			'#attached' => array(
				'library' =>  array(
					'cp_events/style',
					'cp_events/script'
				),
			),
		);
	}
	
	function _build_html($list) {
	
		$output = '<div id="cp_events">';
	
		$url = $GLOBALS['base_url'] . '/' . PublicStream::basePath() . '/';
	
		foreach ($list as $e) {
			
			$output .= '<div class="all-event">';
			
			if ($e->getFromDate() != null || $e->getFromDate() != '') {
				$output .= '<div class="from_date">' . $e->getFromDate() . '</div>';
			}
			 
			$output .= '<div class="heading">' . $e->getHeading() . '</div>';
			
			
			 
			if ($e->getPictureUrl() != null || $e->getPictureUrl() != '') {
				$picture_url = $url . str_replace('public://', '', $e->getPictureUrl());
			
				$picture_title = '';
				if ($e->getPictureTitle() != null || $e->getPictureTitle() != '') { $picture_title = $e->getPictureTitle(); }
			
				$output .= '<div class="text"><img src="' . $picture_url . '" alt="' . $picture_title . '" title="' . $picture_title . '" />' . $e->getText() . '</div>';
			
			} else {
				$output .= '<div class="text">' . $e->getText() . '</div>';
				
			}
			
			
			
			if ($e->getLinkUrl() != null || $e->getLinkUrl() != '') {
				$link_title = $e->getLinkUrl();
					
				if ($e->getLinkTitle() != null || $e->getLinkTitle() != '') { $link_title = $e->getLinkTitle(); }
					
				$output .= '<div class="link"><a href="' . $e->getLinkUrl() . '">' . $link_title . '</a></div>';
			}
				
			$output .= '</div>';
				
		}
	
		$output .= '</div>';
	
		return $output;
	}
}