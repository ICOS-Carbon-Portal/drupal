<?php

namespace Drupal\cp_cmis\CMIS;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


class Settings extends ConfigFormBase {
	
	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'cp_cmis_settings';
	}	
	
	/**
	 * {@inheritdoc}
	 */
	protected function getEditableConfigNames() {
		return ['cp_cmis.settings'];
	}
	
	
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {
		 
		$settings = $this->config('cp_cmis.settings');
	
		$form['cp_cmis_url'] = array (
			'#type' => 'textfield',
			'#title' => $this->t('URL'),
			'#description' => $this->t(''),
			'#default_value' => $settings->get('url')
		);
		
		$form['cp_cmis_username'] = array (
			'#type' => 'textfield',
			'#title' => $this->t('Username'),
			'#description' => $this->t(''),
			'#default_value' => $settings->get('username')
		);
		
		$form['cp_cmis_password'] = array (
			'#type' => 'textfield',
			'#title' => $this->t('Password'),
			'#description' => $this->t(''),
			'#default_value' => $settings->get('password')
		);
		
		$form['cp_cmis_public_base_path'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('Public base path'),
				'#description' => $this->t(''),
				'#default_value' => $settings->get('public_base_path')
		);
		
		$form['cp_cmis_paths'] = array(
				'#type' => 'textarea',
				'#title' => t('Assign a comma separated list of path\'s.'),
				'#description' => t('Insert a comma separated list of path\'s to the folders you want to list contents from.'),
				'#required' => FALSE,
				'#default_value' => $settings->get('paths')
		);
	
		return parent::buildForm($form, $form_state);	 
	}
	
	
	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state) {
		$settings = $this->config('cp_cmis.settings');
		
		$settings->set('url', $form_state->getValue('cp_cmis_url'));
		$settings->set('username', $form_state->getValue('cp_cmis_username'));
		$settings->set('password', $form_state->getValue('cp_cmis_password'));
		$settings->set('public_base_path', $form_state->getValue('cp_cmis_public_base_path'));
		$settings->set('paths', $form_state->getValue('cp_cmis_paths'));
		$settings->save();
		
		parent::submitForm($form, $form_state);
	}
}