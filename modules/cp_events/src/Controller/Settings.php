<?php

namespace Drupal\cp_events\Controller;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class Settings extends FormBase {
	
	public function getFormId() {
		return 'cp_events_settings';
	}
	
	public function buildForm(array $form, FormStateInterface $form_state) {
		 
		$settings = \Drupal::service('config.factory')->getEditable('cp_events.settings');
		$date_format = 'year-month-day';
		if ($settings->get('settings.date_format') == 'day-month-year') { $date_format = 'day-month-year'; }
		
		$date_format_options = array('year-month-day' => 'year-month-day', 'day-month-year' => 'day-month-year');
	
		$form['cp_events_date_format'] = array (
				'#type' => 'select',
				'#title' => $this->t('Select a date format'),
				'#description' => '',
				'#options' => $date_format_options,
				'#default_value' => $date_format
		);
		
		$form['save'] = array(
				'#type' => 'submit',
				'#value' => $this->t('Save'),
		);
	
		return $form;	 
	}
	
	public function validateForm(array &$form, FormStateInterface $form_state) {
		
	}
	
	public function submitForm(array &$form, FormStateInterface $form_state) {
		$settings = \Drupal::service('config.factory')->getEditable('cp_events.settings');
		$settings->set('settings.date_format', $form_state->getValue('cp_events_date_format'));
		$settings->save();
		
	}
}