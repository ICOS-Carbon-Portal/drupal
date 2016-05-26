<?php

namespace Drupal\cp_events\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_events\CPEvents\SortedListOfEvents;
use Drupal\Core\StreamWrapper\PublicStream;

use Drupal\Core\Datetime\DateFormatter;

/**
 * @Block(
 *   id = "list_of_cp_events",
 *   admin_label = @Translation("List of CP events"),
 * )
 */
class ListOfCpEvents extends BlockBase {
	
	function build() {
		$listOfEvents = new SortedListOfEvents();
		$list = $listOfEvents->getListLatestLast();
	
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
			
			if ($e->getNews() == 0 && $e->getHistorical() == 0) {
				
				$output .= '<div class="full-event">';
			
				$from_date = '';
				if ($e->getFromDate() != null || $e->getFromDate() != '') {
					$from_date = $e->getFromDate();
					
				} else {
					$from_date = date('Y-m-d', $e->getCreated());
				}
				
				$to_date = '';
				if ($e->getToDate() != null || $e->getToDate() != '') {
					$to_date = ' -- ' . $e->getToDate();
				
				}
				
				$output .= '<div class="from_date">' . $from_date . $to_date . '</div>';
				
				$output .= '<div class="heading"><a href="/event/'.$e->getId().'">' . $e->getTitle() . '</a></div>';
				
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
				
				if ($e->getLinkUrl() != null || $e->getLinkUrl() != '') {
					$link_title = $e->getLinkUrl();
						
					if ($e->getLinkTitle() != null || $e->getLinkTitle() != '') { $link_title = $e->getLinkTitle(); }
						
					$output .= '<div class="link"><a href="' . $e->getLinkUrl() . '">' . $link_title . '</a></div>';
				}
				
				$output .= '</div>';
			}			
		}
	
		$output .= '</div>';
	
		return $output;
	}
}