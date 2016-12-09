<?php

namespace Drupal\cp_statistics\Controller;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class Settings extends FormBase {
	
	public function getFormId() {
		return 'cp_statistics_settings';
	}
	
	public function buildForm(array $form, FormStateInterface $form_state) {
		 
		$settings = \Drupal::service('config.factory')->getEditable('cp_statistics.settings');
		$portal = $settings->get('settings.portal');
		
		$form['cp_statistics_portal'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('Type the portal chars'),
				'#description' => '',
				'#default_value' => $portal
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
		$settings = \Drupal::service('config.factory')->getEditable('cp_statistics.settings');
		$settings->set('settings.portal', $form_state->getValue('cp_statistics_portal'));
		$settings->save();
		
	}
}