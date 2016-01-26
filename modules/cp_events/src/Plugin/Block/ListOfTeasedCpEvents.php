<?php

namespace Drupal\cp_events\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_events\CPEvents\SortedListOfEvents;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *   id = "list_of_teased_cp_events",
 *   admin_label = @Translation("List of teased CP events"),
 * )
 */
class ListOfTeasedCpEvents extends BlockBase {
	
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
		$config = $this->getConfiguration();
		
		$counter = '5';
		if (isset($config['cp_events_teased_events_counts'])) {
			$counter = $config['cp_events_teased_events_counts'];
		}
		
		$output = '<div id="cp_events">';
	
		$url = $GLOBALS['base_url'] . '/' . PublicStream::basePath() . '/';
	
		$co = 0;
		foreach ($list as $e) {
			
			if ($co < $counter && $e->getNews() == 0 && $e->getHistorical() == 0) {	
				
				$output .= '<div class="tease-event">';
				
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
				
				$co ++;
			}	
		}
	
		$output .= '</div>';
	
		return $output;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function blockForm($form, FormStateInterface $form_state) {
		$config = $this->getConfiguration();
		
		$counter = '5';
		if (isset($config['cp_events_teased_events_counts'])) {
			$counter = $config['cp_events_teased_events_counts'];
		}
		
		$form = parent::blockForm($form, $form_state);
		
		$form['cp_events_teased_events_counts'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('Type the number of events to show'),
				'#description' => $this->t(''),
				'#default_value' => $counter
		);
	
		return $form;	 
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function blockSubmit($form, FormStateInterface $form_state) {
		$this->setConfigurationValue('cp_events_teased_events_counts', $form_state->getValue('cp_events_teased_events_counts'));
	}
}