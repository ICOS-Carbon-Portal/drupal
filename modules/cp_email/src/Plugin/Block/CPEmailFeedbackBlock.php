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

	public function blockForm($form, FormStateInterface $form_state) {
		
		$form = parent::blockForm($form, $form_state);
		$settings = \Drupal::service('config.factory')->getEditable('cp_email.settings');
		
		$form['receiver'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('The receiver email'),
				'#default_value' => $settings->get('settings.feedback_receiver')
		);
		
		$form['subject'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('The subject'),
				'#default_value' => $settings->get('settings.feedback_subject')
		);
	
		return $form;	 
	}
	
	public function blockSubmit($form, FormStateInterface $form_state) {
		$settings = \Drupal::service('config.factory')->getEditable('cp_email.settings');
		$settings->set('settings.feedback_receiver', $form_state->getValue('receiver'));
		$settings->set('settings.feedback_subject', $form_state->getValue('subject'));
		$settings->save();
	}
}