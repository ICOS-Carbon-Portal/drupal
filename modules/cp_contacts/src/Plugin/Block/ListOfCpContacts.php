<?php

namespace Drupal\cp_contacts\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *   id = "list_of_cp_contacts",
 *   admin_label = @Translation("Contact list"),
 * )
 */
class ListOfCpContacts extends BlockBase {

	function build() {

		$config = $this->getConfiguration();
		$contact_group = $config['cp_contact_contact_group'];
		$contact_module_path = \Drupal::service('extension.list.module')->getPath('cp_contacts');
		$default_picture = \Drupal::service('file_url_generator')->generateAbsoluteString($contact_module_path . '/images/person_male.jpg');

		$node_view_builder = \Drupal::entityTypeManager()->getViewBuilder('node');
		$nids = \Drupal::entityQuery('node')
			->accessCheck(TRUE)
			->condition('type', 'cp_contact')
			->condition('status', 1)
			->condition('field_cp_contact_group', $contact_group)
			->sort('field_cp_contact_index')
			->execute();
		$nodes = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids);

		$list = [];
		foreach ($nodes as $node) {
			$list[] = $node_view_builder->view($node, 'default')['#node'];
		}

		return array(
			'#theme' => 'contacts_block',
			'#attached' => array(
				'library' =>  array(
					'cp_contacts/style'
				),
			),
			'#cache' => array(
				'tags' => ['node_list:cp_contact']
			),
			'#list' => $list,
			'#default_picture' => $default_picture
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function blockForm($form, FormStateInterface $form_state) {
		$config = $this->getConfiguration();

		$form = parent::blockForm($form, $form_state);

		$contact_options = array();
		$contact_options['none'] = '';

		$field_definitions = \Drupal::service('entity_field.manager')->getFieldDefinitions('node', 'cp_contact');
		if (isset($field_definitions['field_cp_contact_group'])) {
			$contact_options = options_allowed_values($field_definitions['field_cp_contact_group']->getFieldStorageDefinition());
		}

		$contact_group = '';
		if (isset($config['cp_contact_contact_group'])) {
			$contact_group = $config['cp_contact_contact_group'];
		}

		$form['cp_contact_contact_group'] = array (
				'#type' => 'select',
				'#title' => $this->t('Select a group of contacts'),
				'#options' => $contact_options,
				'#default_value' => $contact_group
		);

		return $form;
	}


	/**
	 * {@inheritdoc}
	 */
	public function blockSubmit($form, FormStateInterface $form_state) {
		$this->setConfigurationValue('cp_contact_contact_group', $form_state->getValue('cp_contact_contact_group'));
	}

}
