<?php

namespace Drupal\cp_statistics\Form;

use Drupal\cp_statistics\CPStatistics\InternalDataService;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ChangedCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\DataCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;


class CPStatisticsForm extends FormBase {

	public function getFormId() {
		return 'cp_statistics_page_form';
	}

	public function buildForm(array $form, FormStateInterface $form_state) {

		$form['cp_statistics_page_year'] = array(
			'#type' => 'select',
			'#title' => 'Select a year',
			//'#description' => 'Select a year',
			'#options' => $this->getYears(),
			'#ajax' => array(
				'callback' => 'Drupal\cp_statistics\Form\CPStatisticsForm::loadByYear',
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

	public function validateForm(array &$form, FormStateInterface $form_state) {}

	public function submitForm(array &$form, FormStateInterface $form_state) {}

	public function getYears() {
		
		$data = new InternalDataService();
		$years = $data->getYears();
		
		foreach ($years['years'] as $year) {
			$list[$year] = $year;
		}
		return $list;
	}

	public function loadByYear(array &$form, FormStateInterface $form_state) {
		$response = new AjaxResponse();
		$response->addCommand(new HtmlCommand('#cp_statistics_selected_year', $form_state->getValue('cp_statistics_page_year')));

		return $response;
	}
}
