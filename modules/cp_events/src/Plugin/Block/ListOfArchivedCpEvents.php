<?php

namespace Drupal\cp_events\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_events\CPEvents\SortedListOfEvents;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *   id = "list_of_archived_cp_events",
 *   admin_label = @Translation("List of archived CP events"),
 * )
 */
class ListOfArchivedCpEvents extends BlockBase {

	function build() {
		$config = $this->getConfiguration();
		
		$events_category = '';
		if (isset($config['cp_events_archived_category'])) {		
			if ($config['cp_events_archived_category'] == 'Events') {
				$events_category = 0;
			}
			
			if ($config['cp_events_archived_category'] == 'News') {
				$events_category = 1;
			}	
		}
		
		$date_format = 'Y-m-d';
		if (isset($config['cp_events_date_format'])) {
			if ($config['cp_events_date_format'] == 'day-month-year') { $date_format = 'd-m-Y'; }
		}
		
		$listOfEvents = new SortedListOfEvents();
		$list = $listOfEvents->getListLatestFirst();
		
		$list_of_elements = array();
		
		foreach ($list as $e) {
			if ($e->getHistorical() == 1) {
				
				if ($config['cp_events_archived_category'] == 'Events and news' || $e->getNews() == $events_category) {
				
					$date = '';
					if ($e->getFromDate() != null && $e->getFromDate() != '') {
						$date = date($date_format, strtotime($e->getFromDate()));
					
					} else {
						$date = date($date_format, $e->getChanged());
						
					}
					
					$e->setFromDate($date);
					
					if ($e->getToDate() != null && $e->getToDate() != '') {
						$to_date = date($date_format, strtotime($e->getToDate()));
						$e->setToDate($to_date);
					}
					
					
					$dateTime = new \DateTime($date);
					$year = $dateTime->format('Y');
					
					$list_of_elements[$year][] = $e;
				}
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
				'#cache' => array(
						'max-age' => 1
				),
		);
	}
	
	
	/**
	 * {@inheritdoc}
	 */
	public function blockForm($form, FormStateInterface $form_state) {
		$config = $this->getConfiguration();
	
		$form = parent::blockForm($form, $form_state);
	
		$events_options = array('Events and news' => 'Events and news', 'Events' => 'Events', 'News' => 'News');
		
	
		$events_category = '';
		if (isset($config['cp_events_archived_category'])) {
			$events_category = $config['cp_events_archived_category'];
		}
	
		$form['cp_events_archived_category'] = array (
				'#type' => 'select',
				'#title' => $this->t('Select a category'),
				'#description' => '',
				'#options' => $events_options,
				'#default_value' => $events_category
		);
	
		$date_format = '';
		if (isset($config['cp_events_date_format'])) {
			$date_format = $config['cp_events_date_format'];
		}
	
		$date_format_options = array('year-month-day' => 'year-month-day', 'day-month-year' => 'day-month-year');
	
		$form['cp_events_date_format'] = array (
				'#type' => 'select',
				'#title' => $this->t('Select a date format'),
				'#description' => '',
				'#options' => $date_format_options,
				'#default_value' => $date_format
		);
	
		return $form;
	}
	
	
	/**
	 * {@inheritdoc}
	 */
	public function blockSubmit($form, FormStateInterface $form_state) {
		$this->setConfigurationValue('cp_events_archived_category', $form_state->getValue('cp_events_archived_category'));
		$this->setConfigurationValue('cp_events_date_format', $form_state->getValue('cp_events_date_format'));
	}	
}