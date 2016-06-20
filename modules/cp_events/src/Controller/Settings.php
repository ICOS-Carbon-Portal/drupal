<?php

namespace Drupal\cp_events\Controller;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


class Settings extends ConfigFormBase {
	
	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'cp_events_settings';
	}	
	
	/**
	 * {@inheritdoc}
	 */
	protected function getEditableConfigNames() {
		return ['cp_events.settings'];
	}
	
	
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {
		 
		$config = $this->config('cp_events.settings');
		
		$date_format = '';
		if ($config->get('cp_events_page_date_format') != null) {
			$date_format = $config->get('cp_events_page_date_format');
		}
	
		$date_format_options = array('year-month-day' => 'year-month-day', 'day-month-year' => 'day-month-year');
	
		$form['cp_events_page_date_format'] = array (
				'#type' => 'select',
				'#title' => $this->t('Select a date format'),
				'#description' => '',
				'#options' => $date_format_options,
				'#default_value' => $date_format
		);
	
		return parent::buildForm($form, $form_state);	 
	}
	
	
	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state) {
		$config = $this->config('cp_events.settings');
		
		$config->set('cp_events_page_date_format', $form_state->getValue('cp_events_page_date_format'));
		$config->save();
		
		parent::submitForm($form, $form_state);
	}
}