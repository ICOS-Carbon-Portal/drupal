<?php

namespace Drupal\cp_email\Plugin\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\smtp\Plugin\Mail\SMTPMailSystem;

class CPEmailFeedbackForm extends FormBase {
  
	/**
	 * {@inheritdoc}.
	 */
	public function getFormId() {
    	return 'cp_email_feedback_form';
	}
  
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {
  	
  		$sender = '';
  		$disabled = false;
  	
  		$user = \Drupal::currentUser();
  		if ($user->isAuthenticated()) {
  			$sender = $user->getEmail();
  			$disabled = true;
  		}
  	
  		$form['sender'] = array (
  			'#type' => 'textfield',
  			'#title' => t('Your email address'),
  			'#required' => true,
  			'#disabled' => $disabled,
  			'#default_value' => $sender,
  			'#element_validate' => array('validateForm'),
  			
  		);
  
  		$form['message'] = array (
  			'#type' => 'textarea',
  			'#title' => t('Your message'),
  			'#required' => true,
  			'#cols' => 10,
  			'#rows' => 5,
  		);
  
  		$form['submit'] = array(
  			'#type' => 'submit',
  			'#value' => t('Submit'),
  		);
  	
  		return $form;
  	}
  
	/**
	 * {@inheritdoc}
	 */
	public function validateForm(array &$form, FormStateInterface $form_state) {
  		if (! valid_email_address($form_state->getValue('sender'))) {
  			$form_state->setErrorByName('email-error', t('This is not a valid email address.'));
  		}
  	 
  		return true;
  	}  
  
	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state) {
  		$sender = $form_state->getValue('sender');
		$message = $form_state->getValue('message');

		$config = \Drupal::service('config.factory')->getEditable('cp_email.settings');
		$receiver = $config->get('settings.feedback_receiver');
		$subject = $config->get('settings.feedback_subject');
	
		if ($receiver != '') {
			
			$handler = new SMTPMailSystem();
			
			$email = array();
			$email['id'] = 'id-1';
			$email['headers']['From'] = $sender;
			$email['headers']['Reply-To'] = $sender;
			$email['from'] = $sender;
			$email['to'] = $receiver;
			$email['subject'] = $subject;
			$email['body'] = $message;
			
			$sent = 'Your feedback has been sent, thank you.';
			
			if (! $handler->mail($email)) {
				$sent = 'An error has occurred. Please try again about a minute.';
			}
			
			drupal_set_message($sent);
		}
	} 
}