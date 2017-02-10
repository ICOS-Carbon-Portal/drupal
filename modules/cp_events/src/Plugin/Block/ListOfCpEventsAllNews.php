<?php

namespace Drupal\cp_events\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_events\CPEvents\SortedListOfEvents;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *   id = "list_of_cp_events_all_news",
 *   admin_label = @Translation("List of all CP news"),
 * )
 */
class ListOfCpEventsAllNews extends BlockBase {
	
	function build() {
		$listOfEvents = new SortedListOfEvents();
		$list = $listOfEvents->getListLatestFirst();
		
		$config = $this->getConfiguration();
		
		$date_format = 'Y-m-d';
		if (isset($config['cp_events_all_news_date_format'])) {
			if ($config['cp_events_all_news_date_format'] == 'day-month-year') { $date_format = 'd-m-Y'; }
		}
		
		$list_of_elements = array();
		
		foreach ($list as $e) {
				
			if ($e->getNews() != 0) {
					
				$date = '';
				if ($e->getFromDate() != null && $e->getFromDate() != '') {
					$date = $e->getFromDate();
		
				} else {
					$date = date('Y-m-d', $e->getChanged());
					$e->setFromDate($date);
				}
		
				$dateTime = new \DateTime($date);
				$year = $dateTime->format('Y');
				
				$list_of_elements['year'][$year][] = $e;
			}
		}
		
		return array(
			'#theme' => 'cp_events_all_news',
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
	
		$date_format = '';
		if (isset($config['cp_events_all_news_date_format'])) {
			$date_format = $config['cp_events_all_news_date_format'];
		}
	
		$date_format_options = array('year-month-day' => 'year-month-day', 'day-month-year' => 'day-month-year');
	
		$form['cp_events_all_news_date_format'] = array (
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
		$this->setConfigurationValue('cp_events_all_news_date_format', $form_state->getValue('cp_events_all_news_date_format'));
	}	
}