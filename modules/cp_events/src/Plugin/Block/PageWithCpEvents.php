<?php

namespace Drupal\cp_events\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_events\CPEvents\SortedListOfEvents;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *   id = "page_with_cp_events",
 *   admin_label = @Translation("Page with CP events"),
 * )
 */
class PageWithCpEvents extends BlockBase {
	
	function build() {
		$config = $this->getConfiguration();
		
		$listOfEvents = new SortedListOfEvents();
		$list = $listOfEvents->getListLatestLast();
		
		$date_format = 'Y-m-d';
		if (isset($config['cp_events_date_format'])) {
			if ($config['cp_events_date_format'] == 'day-month-year') { $date_format = 'd-m-Y'; }
		}
		
		$url = '/' . PublicStream::basePath() . '/';
		
		$events = array();
		$news = array();
		
		$now = time();
		
		foreach ($list as $e) {
			if ($e->getHistorical() == 0) {
				
				if ($e->getFromDate() == null || $e->getFromDate() == '') {
					$e->setFromDate( date($date_format, $e->getCreated()) );
				}
				
				$from_date = date($date_format, strtotime($e->getFromDate()));
				$e->setFromDate($from_date);
				
				if ($e->getToDate() != null && $e->getToDate() != '') {
					$to_date = date($date_format, strtotime($e->getToDate()));
					$e->setToDate($to_date);
				}
					
				if ($e->getPictureUri() != null && $e->getPictureUri() != '') {
					$picture_url = $url . str_replace('public://', '', $e->getPictureUri());
					$e->setPictureUri($picture_url);
				}
				
				if ($e->getNews() != 1) {
					
					if ($e->getToDate() != null && $e->getToDate() != '') {
						if (strtotime($e->getToDate()) > $now) {
							$events[] = $e;
						}
					} else {
						$events[] = $e;
					}
					
				} else {
					$news[] = $e;
				}
			}
		}
	
		$list_of_elements['events']['list'] = $events;
		$list_of_elements['events']['count'] = $config['cp_events_page_events_counts'];
		$list_of_elements['news']['list'] = array_reverse($news);
		$list_of_elements['news']['count'] = $config['cp_events_page_news_counts'];
		
		$list_of_elements['site_home'] = "https://$_SERVER[HTTP_HOST]";
		
		return array(
				'#theme' => 'page_with_cp_events',
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
		
		$events_counter = '5';
		if (isset($config['cp_events_page_events_counts'])) {
			$events_counter = $config['cp_events_page_events_counts'];
		}
		
		$form['cp_events_page_events_counts'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('Type the number of events to show'),
				'#description' => $this->t(''),
				'#default_value' => $events_counter
		);
		
		$news_counter = '5';
		if (isset($config['cp_events_page_news_counts'])) {
			$news_counter = $config['cp_events_page_news_counts'];
		}
		
		$form['cp_events_page_news_counts'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('Type the number of news to show'),
				'#description' => $this->t(''),
				'#default_value' => $news_counter
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
		$this->setConfigurationValue('cp_events_date_format', $form_state->getValue('cp_events_date_format'));
		$this->setConfigurationValue('cp_events_page_events_counts', $form_state->getValue('cp_events_page_events_counts'));
		$this->setConfigurationValue('cp_events_page_news_counts', $form_state->getValue('cp_events_page_news_counts'));
	}	
}