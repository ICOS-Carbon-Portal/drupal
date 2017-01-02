<?php

namespace Drupal\cp_email\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *   id = "cp_email",
 *   admin_label = @Translation("CP email"),
 * )
 */
class CpEmailBlock extends BlockBase {
	
	function build() {
		$config = $this->getConfiguration();
		
		$build['#markup'] = '';
		$build['form'] = \Drupal::formBuilder()->getForm('\Drupal\cp_email\Plugin\Form\CPEmailForm', $config);
		$build['#attached']['library'][] = 'cp_email/style';
		$build['#attached']['library'][] = 'cp_email/script';
		
		if ($config['cp_email_human_control'] && $config['cp_email_human_control_key'] != '') {
			$build['#markup'] = $this->_build_html($config['cp_email_human_control_key'], $config['cp_email_human_control_label']);
			$build['#attached']['drupalSettings'] = array(
					'human_control_key' => $config['cp_email_human_control_key'],
					'type' => 'setting',
			);			
		}
		
		return $build;
	}
	
	function _build_html($key, $label) {
		$output = '<p class="human_control_element_label">' . $label . '</p>';
		$output .= '<div id="human_control_element_' . $key . '" class="human_control_element">';
		$output .= '<form action="?" method="post"><div class="g-recaptcha" data-sitekey="' . $key . '" data-callback="verifyHumanControl"></div><br/><input type="submit" value="Submit"></form>';
		$output .= '</div>';
		
		return $output;
	}
	
	public function blockForm($form, FormStateInterface $form_state) {
	
		$form = parent::blockForm($form, $form_state);
		$config = $this->getConfiguration();
		
		$id = microtime();
		if (isset($config['cp_email_id'])) {
			$id = $config['cp_email_id'];
		}
		
		$receiver = '';
		if (isset($config['cp_email_receiver'])) {
			$receiver = $config['cp_email_receiver'];
		}
		
		$subject = '';
		if (isset($config['cp_email_subject'])) {
			$subject = $config['cp_email_subject'];
		}
		
		$label_sender = 'Your email address';
		if (isset($config['cp_email_label_sender'])) {
			$label_sender = $config['cp_email_label_sender'];
		}
		
		$label_message = 'Your message';
		if (isset($config['cp_email_label_message'])) {
			$label_message = $config['cp_email_label_message'];
		}
		
		$complete_message = 'Your email has been sent.';
		if (isset($config['cp_email_complete_message'])) {
			$complete_message = $config['cp_email_complete_message'];
		}
		
		$human_control = 0;
		if (isset($config['cp_email_human_control'])) {
			$human_control = $config['cp_email_human_control'];
		}
		
		$human_control_key = '';
		if (isset($config['cp_email_human_control_key'])) {
			$human_control_key = $config['cp_email_human_control_key'];
		}
		
		$human_control_label;
		if (isset($config['cp_email_human_control_label'])) {
			$human_control_label = $config['cp_email_human_control_label'];
		}
		
		$form['cp_email_id'] = array (
				'#type' => 'hidden',
				'#value' => $id
		);
	
		$form['cp_email_receiver'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('The receiver email'),
				'#default_value' => $receiver
		);
	
		$form['cp_email_subject'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('The subject'),
				'#default_value' => $subject
		);
		
		$form['cp_email_label_sender'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('Label for the sender email'),
				'#default_value' => $label_sender
		);
		
		$form['cp_email_label_message'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('Label for the message'),
				'#default_value' => $label_message
		);
		
		$form['cp_email_complete_message'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('The complete message'),
				'#default_value' => $complete_message
		);
		
		$form['cp_email_human_control'] = array(
				'#type' => 'checkbox',
				'#title' => $this->t('Test against robots'),
				'#default_value' => $human_control
		);
		
		$form['cp_email_human_control_key'] = array(
				'#type' => 'textfield',
				'#title' => $this->t('The control secret key'),
				'#default_value' => $human_control_key
		);
		
		$form['cp_email_human_control_label'] = array(
				'#type' => 'textfield',
				'#title' => $this->t('Label for the human control'),
				'#default_value' => $human_control_label
		);
	
		return $form;
	}
	
	public function blockSubmit($form, FormStateInterface $form_state) {
		$this->setConfigurationValue('cp_email_id', $form_state->getValue('cp_email_id'));
		$this->setConfigurationValue('cp_email_receiver', $form_state->getValue('cp_email_receiver'));
		$this->setConfigurationValue('cp_email_subject', $form_state->getValue('cp_email_subject'));
		$this->setConfigurationValue('cp_email_label_sender', $form_state->getValue('cp_email_label_sender'));
		$this->setConfigurationValue('cp_email_label_message', $form_state->getValue('cp_email_label_message'));
		$this->setConfigurationValue('cp_email_complete_message', $form_state->getValue('cp_email_complete_message'));
		$this->setConfigurationValue('cp_email_human_control', $form_state->getValue('cp_email_human_control'));
		$this->setConfigurationValue('cp_email_human_control_key', $form_state->getValue('cp_email_human_control_key'));
		$this->setConfigurationValue('cp_email_human_control_label', $form_state->getValue('cp_email_human_control_label'));
	}
	
}