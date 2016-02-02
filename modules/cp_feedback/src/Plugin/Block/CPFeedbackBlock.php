<?php

namespace Drupal\cp_feedback\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *   id = "cp_feedback_form",
 *   admin_label = @Translation("CP feedback form"),
 * )
 */
class CpFeedbackBlock extends BlockBase {
	
	function build() {

		$build['#markup'] = '';
		$build['form'] = \Drupal::formBuilder()->getForm('\Drupal\cp_feedback\Plugin\Form\CPFeedbackForm');
		$build['#attached']['library'][] = 'cp_feedback/style';
		return $build;
		
	}

	/**
	 * {@inheritdoc}
	 */
	public function blockForm($form, FormStateInterface $form_state) {
		
		$form = parent::blockForm($form, $form_state);
		$config = \Drupal::service('config.factory')->getEditable('cp_feedback.settings');
		
		$form['email'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('Email to the receiver'),
				'#default_value' => $config->get('settings.email')
		);
		
		$form['subject'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('Subject'),
				'#default_value' => $config->get('settings.subject')
		);
	
		return $form;	 
	}
	
	
	/**
	 * {@inheritdoc}
	 */
	public function blockSubmit($form, FormStateInterface $form_state) {
		$config = \Drupal::service('config.factory')->getEditable('cp_feedback.settings');
		$config->set('settings.email', $form_state->getValue('email'));
		$config->set('settings.subject', $form_state->getValue('subject'));
		$config->save();
	}
}