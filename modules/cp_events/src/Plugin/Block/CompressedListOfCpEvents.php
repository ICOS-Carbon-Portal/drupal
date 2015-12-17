<?php

namespace Drupal\cp_events\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_events\CPEvents\ListOfCPEvents;
use Drupal\Core\StreamWrapper\PublicStream;

/**
 * @Block(
 *   id = "compressed_list_of_cp_events",
 *   admin_label = @Translation("Compressed list of CP events"),
 * )
 */
class CompressedListOfCpEvents extends BlockBase {
	
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
			
			if ($e->getHistorical() == 0) {	
				
				$output .= '<div class="comp-event">';
				
				if ($e->getFromDate() != null || $e->getFromDate() != '') {
					$output .= '<div class="from_date">' . $e->getFromDate() . '</div>';
				}
				
				$output .= '<div class="heading"><a href="/event/'.$e->getId().'">' . $e->getHeading() . '</a></div>';
				
				if ($e->getPictureUrl() != null || $e->getPictureUrl() != '') {
					$picture_url = $url . str_replace('public://', '', $e->getPictureUrl());
					
					$picture_title = '';
					if ($e->getPictureTitle() != null || $e->getPictureTitle() != '') { $picture_title = $e->getPictureTitle(); }
					
					$output .= '<div class="picture">';
					$output .= '<img src="' . $picture_url . '" alt="' . $picture_title . '" title="' . $picture_title . '" />';
					$output .= '</div>';
				}
				
				$text = $e->getText();
				if (strlen($e->getText()) > 199 ) {
					$text = substr($e->getText(), 0, 199);
					$text .= '<br/><a href="/event/' . $e->getId() . '">Read more..</a>';
				}
				
				$output .= '<div class="text">' . $text . '</div>';
					
				$output .= '</div>';
			}	
		}
	
		$output .= '</div>';
	
		return $output;
	}
}