<?php

namespace Drupal\cp_contacts\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_contacts\Plugin\Block\Contact;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\Cache;

/**
 * @Block(
 *   id = "list_of_cp_contacts",
 *   admin_label = @Translation("List of CP contacts"),
 * )
 */
class ListOfCpContacts extends BlockBase {
	
	private $default_visible_elements = array('title' => 'Title', 'organization' => 'Organization', 'address' => 'Address', 'email' => 'Email', 'phone' => 'Phone', 'photo' => 'Photo');
	
	function build() {
		
		$list = $this->_prepare_contacts();
		
		return array(
			'#markup' => $this->_build_html($list),
			'#attached' => array(
				'library' =>  array(
					'cp_contacts/style',
					'cp_contacts/script'
				),	
			),
			'#cache' => array(
					'max-age' => 1
			),				
		);
	}
	
	function _build_html($list) {
		
		$config = $this->getConfiguration();
		
		$output = '<div class="cp_contacts">';
		
		$url = '/' . PublicStream::basePath() . '/';
		
		$visible_elements = $this->default_visible_elements;
		if (isset($config['cp_contact_visible_elements'])) {
			$visible_elements = $config['cp_contact_visible_elements'];
		}
		
		if (isset($config['cp_contact_contact_group'])) {
			$contact_group = $config['cp_contact_contact_group'];
		
			foreach ($list as $c) {
				if (in_array($contact_group, $c->getGroups())) {
					
					$output .= '<div class="contact">';
					
					$data_to_right = '';
					
					if ($visible_elements['photo']) {
						if ($c->getPhoto()) {
							$photo = $url . str_replace('public://', '', $c->getPhoto());
							
						} else {
							$photo = '/' . drupal_get_path('module', 'cp_contacts') . '/images/person_male.jpg';
						}
						
						$output .= '<div class="picture">';
						$output .= '<img src="' . $photo . '" width="100" height="100" alt="" />';
						$output .= '</div>';
						
						$data_to_right = 'data_to_right';
					}
					
					$output .= '<div class="data ' . $data_to_right . '">';
					$output .= '<div class="name">' . $c->getName() . '</div>';
					
					if ($visible_elements['title']) {
						$output .= '<div class="title">' . $c->getTitle() . '</div>';
					}
					
					if ($visible_elements['organization']) {
						$output .= '<div class="organization">' . $c->getOrganization() . '</div>';
					}
					
					if ($visible_elements['address']) {
						$output .= '<div class="address">' . $c->getAddress() . '</div>';
					}
					
					if ($visible_elements['email']) {
						$output .= '<div class="email"><a href="mailto:' . $c->getEmail() . '">' . $c->getEmail() . '</a></div>';
					}
					
					if ($visible_elements['phone']) {
						$output .= '<div class="phone"><a href="tel:' . $c->getPhone() . '">' . $c->getPhone() . '</a></div>';
					}
					
					$output .= '</div>';
					$output .= '</div>';	
				}
			}
		}
		
		$output .= '</div>';
		
		return $output;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function blockForm($form, FormStateInterface $form_state) {
		$config = $this->getConfiguration();
	
		$form = parent::blockForm($form, $form_state);
	
		$contact_options = array();
		$contact_options['none'] = '';
		
		foreach ($this->_get_groups() as $group) {
			$contact_options[$group] = $group;
		}
	
		$contact_group = '';
		if (isset($config['cp_contact_contact_group'])) {
			$contact_group = $config['cp_contact_contact_group'];
		}
	
		$form['cp_contact_contact_group'] = array (
				'#type' => 'select',
				'#title' => $this->t('Select a group of contacts'),
				'#description' => 'It is mandatory to select a group.',
				'#options' => $contact_options,
				'#default_value' => $contact_group
		);
		
		$visible_elements = '';
		if (isset($config['cp_contact_visible_elements'])) {
			$visible_elements = $config['cp_contact_visible_elements'];
		}
		
		$form['cp_contact_visible_elements'] = array (
				'#type' => 'checkboxes',
				'#title' => $this->t('Select elements to be visible'),
				'#options' => $this->default_visible_elements,
				'#default_value' => $visible_elements
		);
	
		return $form;
	}
	
	
	/**
	 * {@inheritdoc}
	 */
	public function blockSubmit($form, FormStateInterface $form_state) {
		$this->setConfigurationValue('cp_contact_contact_group', $form_state->getValue('cp_contact_contact_group'));
		$this->setConfigurationValue('cp_contact_visible_elements', $form_state->getValue('cp_contact_visible_elements'));
	}
	
	function _get_groups() {
		$groups = array();
		
		$result = db_query('
			select distinct field_cp_contact_group_value
			from {node__field_cp_contact_group}
			'
		)->fetchAll();
				
		foreach ($result as $record) {
			if ($record) {
				$groups[] = $record->field_cp_contact_group_value;
			}
		}
		
		return $groups;
	}
	
	function _prepare_contacts() {
		
		$list = array();
		
		foreach ($this->_collect_contacts() as $c) {
			$c = $this->_add_email($c);
			$c = $this->_add_phone($c);
			$c = $this->_add_address($c);
			$c = $this->_add_photo($c);
			$c = $this->_add_title($c);
			$c = $this->_add_organization($c);
			$c = $this->_add_group($c);
			$c = $this->_add_index($c);
				
			$list[] = $c;
		}
		
		usort($list, array($this,'_compare'));
		
		return $list;
	}
	
	static function _compare($a, $b) {
	if($a->getIndex() == null || $a->getIndex() == '') { $a->setIndex(9999999); }
	if($b->getIndex() == null || $b->getIndex() == '') { $b->setIndex(9999999); }
	
		if ($a->getIndex() == $b->getIndex()) {
			return 0;
		}
	
		return $a->getIndex() > $b->getIndex() ? 1 : -1;
	}
	
	function _collect_contacts() {
		
		$list = array();
		
		$result = db_query('
			select n.nid, nfd.title 	
			from {node} as n
			join {node_field_data} as nfd on n.nid = nfd.nid
			where n.type = :type
			and nfd.status = 1
			',
				
			array(':type' => 'cp_contact')
		)->fetchAll();
		
		
		foreach ($result as $record) {
			if ($record) {
				$contact = new Contact();
				$contact->setId($record->nid);
				$contact->setName($record->title);
				
				$list[] = $contact;
			}
		}
		
		return $list;	
	}
	
	function _add_email($contact) {
		
		$result = db_query('
			select field_cp_contact_email_value
			from {node__field_cp_contact_email}
			where entity_id = :id
			',
		
			array(':id' => $contact->getId())
		)->fetchAll();
		
		
		foreach ($result as $record) {
			if ($record) {
				$contact->setEmail($record->field_cp_contact_email_value);
			}
		}
		
		return $contact;
	}
	
	function _add_phone($contact) {
	
		$result = db_query('
			select field_cp_contact_phone_value
			from {node__field_cp_contact_phone}
			where entity_id = :id
			',
	
			array(':id' => $contact->getId())
		)->fetchAll();
	
	
		foreach ($result as $record) {
			if ($record) {
				$contact->setPhone($record->field_cp_contact_phone_value);
			}
		}

		return $contact;
	}

	function _add_address($contact) {
	
		$result = db_query('
			select field_cp_contact_address_value
			from {node__field_cp_contact_address}
			where entity_id = :id
			',
	
			array(':id' => $contact->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$contact->setAddress($record->field_cp_contact_address_value);
			}
		}

		return $contact;
	}
	
	function _add_photo($contact) {
	
		$result = db_query('
			select file.uri
			from {node__field_cp_contact_photo} photo
			join {file_managed} file
			on photo.field_cp_contact_photo_target_id = file.fid
			where photo.entity_id = :id
			',
	
			array(':id' => $contact->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$contact->setPhoto($record->uri);
			}
		}

		return $contact;
	}
	
	function _add_title($contact) {
	
		$result = db_query('
			select field_cp_contact_title_value
			from {node__field_cp_contact_title}
			where entity_id = :id
			',
	
			array(':id' => $contact->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$contact->setTitle($record->field_cp_contact_title_value);
			}
		}

		return $contact;
	}	
	
	function _add_organization($contact) {
	
		$result = db_query('
			select field_cp_contact_organization_value
			from {node__field_cp_contact_organization}
			where entity_id = :id
			',
	
			array(':id' => $contact->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$contact->setOrganization($record->field_cp_contact_organization_value);
			}
		}

		return $contact;
	}

	function _add_group($contact) {
	
		$result = db_query('
			select field_cp_contact_group_value
			from {node__field_cp_contact_group}
			where entity_id = :id
			',
	
			array(':id' => $contact->getId())
		)->fetchAll();

		$groups = array();
		foreach ($result as $record) {
			if ($record) {
				$groups[] = $record->field_cp_contact_group_value;
			}
		}
		$contact->setGroups($groups);
	
		return $contact;
	}
	
	function _add_index($contact) {
	
		$result = db_query('
			select field_cp_contact_index_value
			from {node__field_cp_contact_index}
			where entity_id = :id
			',
	
			array(':id' => $contact->getId())
		)->fetchAll();


		foreach ($result as $record) {
			if ($record) {
				$contact->setIndex($record->field_cp_contact_index_value);
			}
		}

		return $contact;
	}	
}