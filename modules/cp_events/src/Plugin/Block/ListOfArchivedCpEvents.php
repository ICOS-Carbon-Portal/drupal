<?php

namespace Drupal\cp_events\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_events\CPEvents\SortedListOfEvents;
use Drupal\Core\StreamWrapper\PublicStream;

/**
 * @Block(
 *   id = "list_of_archived_cp_events",
 *   admin_label = @Translation("List of archived CP events"),
 * )
 */
class ListOfArchivedCpEvents extends BlockBase {

	function build() {
		\Drupal::service('page_cache_kill_switch')->trigger();
		
		$listOfEvents = new SortedListOfEvents();
		$list = $listOfEvents->getListLatestFirst();
		
		$list_of_elements = array();
		
		foreach ($list as $e) {
			if ($e->getHistorical() == 1) {
				
				$date = '';
				if ($e->getFromDate() != null && $e->getFromDate() != '') {
					$date = $e->getFromDate();
				
				} else {
					$date = date('Y-m-d', $e->getChanged());
					$e->setFromDate($date);
				}
				
				$dateTime = new \DateTime($date);
				$year = $dateTime->format('Y');
				
				$list_of_elements[$year][] = $e;
				
			}
		}
		
		return array(
				'#theme' => 'cp_events_archive',
				'#elements' => $list_of_elements,
				'#attached' => array(
						'library' =>  array(
								'cp_events/style',
								'cp_events/script'
						),
				),
		);
	}
}
