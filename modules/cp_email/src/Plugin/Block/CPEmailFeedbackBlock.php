<?php

namespace Drupal\cp_email\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *   id = "cp_email_feedback_form",
 *   admin_label = @Translation("CP email feedback form"),
 * )
 */
class CpEmailFeedbackBlock extends BlockBase {
	
	function build() {

		$build['#markup'] = '';
		$build['form'] = \Drupal::formBuilder()->getForm('\Drupal\cp_email\Plugin\Form\CPEmailFeedbackForm');
		$build['#attached']['library'][] = 'cp_email/style';
		return $build;
		
	}

	/**
	 * {@inheritdoc}
	 */
	public function blockForm($form, FormStateInterface $form_state) {
		
		$form = parent::blockForm($form, $form_state);
		$config = \Drupal::service('config.factory')->getEditable('cp_email.settings');
		
		$form['receiver'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('The receiver email'),
				'#default_value' => $config->get('settings.feedback_receiver')
		);
		
		$form['subject'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('The subject'),
				'#default_value' => $config->get('settings.feedback_subject')
		);
	
		return $form;	 
	}
	
	
	/**
	 * {@inheritdoc}
	 */
	public function blockSubmit($form, FormStateInterface $form_state) {
		$config = \Drupal::service('config.factory')->getEditable('cp_email.settings');
		$config->set('settings.feedback_receiver', $form_state->getValue('receiver'));
		$config->set('settings.feedback_subject', $form_state->getValue('subject'));
		$config->save();
	}
}