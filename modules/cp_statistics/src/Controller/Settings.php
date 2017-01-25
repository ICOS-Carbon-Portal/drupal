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
		$internal_or_restheart = $settings->get('settings.internal_or_restheart');
		$restheart_post_path = $settings->get('settings.restheart_post_path');
		$restheart_get_path = $settings->get('settings.restheart_get_path');
		
		$form['cp_statistics_internal_or_restheart'] = array (
				'#type' => 'radios',
				'#title' => $this->t('Use internal database or Restheart'),
				'#default_value' => $internal_or_restheart,
				'#options' => array('internal' => $this->t('Internal'), 'restheart' => $this->t('Restheart')),
		);
		
		$form['cp_statistics_restheart_post_path'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('If Restheart then type the path for posting'),
				'#default_value' => $restheart_post_path
		);
		
		$form['cp_statistics_restheart_get_path'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('If Restheart then type the path for getting'),
				'#default_value' => $restheart_get_path
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
		$settings->set('settings.internal_or_restheart', $form_state->getValue('cp_statistics_internal_or_restheart'));
		$settings->set('settings.restheart_post_path', $form_state->getValue('cp_statistics_restheart_post_path'));
		$settings->set('settings.restheart_get_path', $form_state->getValue('cp_statistics_restheart_get_path'));
		$settings->save();
		
	}
}