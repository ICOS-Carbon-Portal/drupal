<?php

namespace Drupal\cp_events\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_events\CPEvents\SortedListOfEvents;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *   id = "list_of_cp_events_as_news",
 *   admin_label = @Translation("List of CP news"),
 * )
 */
class ListOfCpEventsAsNews extends BlockBase {
	
	function build() {
		$listOfEvents = new SortedListOfEvents();
		$list = $listOfEvents->getListLatestFirst();
	
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
	
		$output = '<div id="cp_events_as_news">';
	
		$url = '/' . PublicStream::basePath() . '/';
		
		$date_format = 'Y-m-d';
		if (isset($config['cp_events_as_news_date_format'])) {
			if ($config['cp_events_as_news_date_format'] == 'day-month-year') { $date_format = 'd-m-Y'; }
		}
	
		foreach ($list as $e) {
			
			if ($e->getNews() != 0 && $e->getHistorical() == 0) {
			
				$output .= '<div class="full-event">';
				
				$date = '';
				if ($e->getFromDate() != null && $e->getFromDate() != '') {
					$date = $e->getFromDate();
				
				} else {
					$date = date('Y-m-d', $e->getChanged());
				}
				
				$output .= '<div class="from_date">' . date($date_format, strtotime($date)) . '</div>';
				
				$output .= '<div class="heading"><a href="/event/'.$e->getId().'">' . $e->getTitle() . '</a></div>';
				
				if ($e->getPictureUri() != null && $e->getPictureUri() != '') {
					$picture_url = $url . str_replace('public://', '', $e->getPictureUri());
				
					$picture_title = '';
					if ($e->getPictureTitle() != null && $e->getPictureTitle() != '') { $picture_title = $e->getPictureTitle(); }
						
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
				
				if ($e->getLinkUri() != null && $e->getLinkUri() != '') {
					$link_title = $e->getLinkUri();
						
					if ($e->getLinkTitle() != null && $e->getLinkTitle() != '') { $link_title = $e->getLinkTitle(); }
						
					$output .= '<div class="link"><a href="' . $e->getLinkUri() . '">' . $link_title . '</a></div>';
				}
				
				$output .= '</div>';
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
	
		$form = parent::blockForm($form, $form_state);
	
		$date_format = '';
		if (isset($config['cp_events_as_news_date_format'])) {
			$date_format = $config['cp_events_as_news_date_format'];
		}
	
		$date_format_options = array('year-month-day' => 'year-month-day', 'day-month-year' => 'day-month-year');
	
		$form['cp_events_as_news_date_format'] = array (
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
		$this->setConfigurationValue('cp_events_as_news_date_format', $form_state->getValue('cp_events_as_news_date_format'));
	}	
}