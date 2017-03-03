<?php

namespace Drupal\cp_statistics\Form;

use Drupal\cp_statistics\CPStatistics\InternalDataService;
use Drupal\cp_statistics\CPStatistics\RestheartDataService;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ChangedCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\DataCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;


class CPStatisticsYear extends FormBase {

	public function getFormId() {
		return 'cp_statistics_year_form';
	}

	public function buildForm(array $form, FormStateInterface $form_state) {

		$form['cp_statistics_year'] = array(
			'#type' => 'select',
			'#title' => 'Select a year',
			//'#description' => 'Select a year',
			'#options' => $this->getYears(),
			//'#ajax' => array(
			//	'callback' => 'Drupal\cp_statistics\Form\CPStatisticsYear::loadByYear',
			//	'effect' => 'fade',
			//	'event' => 'change',
			//	'progress' => array(
			//		'type' => 'throbber',
			//		'message' => NULL,
			//	),
			//),
		);

		return $form;
	}

	public function validateForm(array &$form, FormStateInterface $form_state) {}

	public function submitForm(array &$form, FormStateInterface $form_state) {}

	public function getYears() {
		
		$years = $this->getService()->getYears();
		
		foreach ($years['years'] as $year) {
			$list[$year] = $year;
		}
		return $list;
	}

	public function loadByYear(array &$form, FormStateInterface $form_state) {
		$response = new AjaxResponse();
		$response->addCommand(new HtmlCommand('#cp_statistics_title', $form_state->getValue('cp_statistics_year')));

		return $response;
	}
	
	private function getService() {
		$service = '';
	
		$settings = \Drupal::config('cp_statistics.settings');
	
		if ($settings->get('settings.internal_or_restheart') == 'internal') {
			$service = new InternalDataService();
				
		} else if ($settings->get('settings.internal_or_restheart') == 'restheart'
				&& $settings->get('settings.restheart_get_path') != '') {
						
					$service = new RestheartDataService($settings->get('settings.restheart_get_path'));
				}
	
				return $service;
	}
}
