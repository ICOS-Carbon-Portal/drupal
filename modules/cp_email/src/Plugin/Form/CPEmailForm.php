<?php

namespace Drupal\cp_email\Plugin\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\smtp\Plugin\Mail\SMTPMailSystem;

class CPEmailForm extends FormBase {
  	
	public function getFormId() {
    	return 'cp_email_form';
	}
  
	public function buildForm(array $form, FormStateInterface $form_state, $config = null) {
		
  		$sender = '';
  	
  		$user = \Drupal::currentUser();
  		if ($user->isAuthenticated()) {
  			$sender = $user->getEmail();
  		}
  	
  		$form['cp_email_receiver'] = array (
			'#type' => 'hidden',
			'#value' => $config['cp_email_receiver']
  		);
  		
  		$form['cp_email_subject'] = array (
			'#type' => 'hidden',
			'#value' => $config['cp_email_subject']
  		);
  		
  		$form['cp_email_complete_message'] = array (
			'#type' => 'hidden',
			'#value' => $config['cp_email_complete_message']
  		);
  		
  		$form['cp_email_sender'] = array (
  			'#type' => 'textfield',
  			'#title' => t($config['cp_email_label_sender']),
  			'#required' => true,
  			'#default_value' => $sender,	
  		);
  
  		$form['cp_email_message'] = array (
  			'#type' => 'textarea',
  			'#title' => t($config['cp_email_label_message']),
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
  	
  	public function validateForm(array &$form, FormStateInterface $form_state) {
  		if (! valid_email_address($form_state->getValue('cp_email_sender'))) {
  			drupal_set_message($form_state->getValue('cp_email_sender') . ' is not a valid email address.', 'error');
  			
  			return false;
  		}
  		
  		return true;
  	}  	
  
	public function submitForm(array &$form, FormStateInterface $form_state) {
		$receiver = $form_state->getValue('cp_email_receiver');
  		$sender = $form_state->getValue('cp_email_sender');
		
  		if (valid_email_address($sender)) {
  			
	  		if ($receiver != '') {
				
				$handler = new SMTPMailSystem();
				
				$email = array();
				$email['id'] = 'id-1';
				$email['headers']['From'] = $sender;
				$email['headers']['Reply-To'] = $sender;
				$email['from'] = $sender;
				$email['to'] = $receiver;
				$email['subject'] = $form_state->getValue('cp_email_subject');
				$email['body'] = $form_state->getValue('cp_email_message');
				
				if ($handler->mail($email)) {
					drupal_set_message($form_state->getValue('cp_email_complete_message'));
					
				} else {
					drupal_set_message('An error has occurred. Please try again about a minute.', 'error');
				}
			}
			
  		} else {
  			//drupal_set_message($sender . ' is not a valid email address.', 'error');
  		}
	} 
}