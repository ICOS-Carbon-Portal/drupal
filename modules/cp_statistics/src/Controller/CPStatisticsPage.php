<?php

namespace Drupal\cp_statistics\Controller;

use Drupal\cp_statistics\CPStatistics\InternalDataService;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ChangedCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;


class CPStatisticsPage extends FormBase {
	
	public function getFormId() {
		return 'cp_statistics';
	}
	
	public function buildForm(array $form, FormStateInterface $form_state) {
		
		$form['cp_statistics_years'] = array(
				'#type' => 'select',
				'#title' => 'Select year',
				'#options' => [
						'2016' => $this->t('2016'),
						'2017' => $this->t('2017'),
  				],
				'#ajax' => array(
						'callback' => 'Drupal\cp_statistics\Controller\CPStatisticsPage::loadByYear',
						'effect' => 'fade',
						'event' => 'change',
						'progress' => array(
								'type' => 'throbber',
								'message' => NULL,
						),
				),
		);
				
		return $form;
	}
	
	public function validateForm(array &$form, FormStateInterface $form_state) {
	
	}
	
	public function submitForm(array &$form, FormStateInterface $form_state) {
	
	}
	
	public function loadByYear(array &$form, FormStateInterface $form_state) {
		return null;
	}
}