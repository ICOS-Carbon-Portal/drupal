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
		 
		$config = $this->config('cp_cmis.settings');
	
		$form['cp_cmis_url'] = array (
			'#type' => 'textfield',
			'#title' => $this->t('URL'),
			'#description' => $this->t(''),
			'#default_value' => $config->get('url')
		);
		
		$form['cp_cmis_username'] = array (
			'#type' => 'textfield',
			'#title' => $this->t('Username'),
			'#description' => $this->t(''),
			'#default_value' => $config->get('username')
		);
		
		$form['cp_cmis_password'] = array (
			'#type' => 'textfield',
			'#title' => $this->t('Password'),
			'#description' => $this->t(''),
			'#default_value' => $config->get('password')
		);
		
		$form['cp_cmis_paths'] = array(
				'#type' => 'textarea',
				'#title' => t('Assign a comma separated list of path\'s.'),
				'#description' => t('Insert a comma separated list of path\'s to the folders you want to list contents from.'),
				'#required' => FALSE,
				'#default_value' => $config->get('paths')
		);
	
		return parent::buildForm($form, $form_state);	 
	}
	
	
	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state) {
		$config = $this->config('cp_cmis.settings');
		
		$config->set('url', $form_state->getValue('cp_cmis_url'));
		$config->set('username', $form_state->getValue('cp_cmis_username'));
		$config->set('password', $form_state->getValue('cp_cmis_password'));
		$config->set('paths', $form_state->getValue('cp_cmis_paths'));
		$config->save();
		
		parent::submitForm($form, $form_state);
	}
}