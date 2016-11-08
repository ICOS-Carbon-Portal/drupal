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
  	
  		$email = '';
  		$disabled = false;
  	
  		$user = \Drupal::currentUser();
  		if ($user->isAuthenticated()) {
  			$email = $user->getEmail();
  			$disabled = true;
  		}
  	
  		$form['email'] = array (
  			'#type' => 'textfield',
  			'#title' => t('Your email address'),
  			'#required' => true,
  			'#disabled' => $disabled,
  			'#default_value' => $email,
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
  		if (! valid_email_address($form_state->getValue('email'))) {
  			$form_state->setErrorByName('email-error', t('This is not a valid email address.'));
  		}
  	 
  		return true;
  	}  
  
	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state) {
  		$email = $form_state->getValue('email');
		$message = $form_state->getValue('message');

		$config = \Drupal::service('config.factory')->getEditable('cp_email.settings');
		$receiver_email = $config->get('settings.email');
		$subject = $config->get('settings.subject');
	
		if ($receiver_email != '') {
			
			$handler = new SMTPMailSystem();
			
			$mail = array();
			$mail['id'] = 'id-1';
			$mail['headers']['From'] = $email;
			$mail['headers']['Reply-To'] = $email;
			$mail['from'] = $email;
			$mail['to'] = $receiver_email;
			$mail['subject'] = $subject;
			$mail['body'] = $message;
			
			$sent = 'Your feedback has been sent, thank you.';
			
			if (! $handler->mail($mail)) {
				$sent = 'A error has occurred. Please try again about a minute.';
			}
			
			drupal_set_message($sent);
		}
	} 
}