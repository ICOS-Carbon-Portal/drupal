<?php

namespace Drupal\cp_contacts\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_contacts\Plugin\Block\Contact;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\Core\Cache\Cache;

/**
 * @Block(
 *   id = "list_of_cp_contacts",
 *   admin_label = @Translation("List of CP contacts"),
 * )
 */
class ListOfCpContacts extends BlockBase {
	
	function build() {
		
		$list = $this->_prepare_contacts();
		
		Cache::invalidateTags(array('block_view config:block.block.listofcpcontacts', 'config:block.block.listofcpcontacts'));
		
		return array(
			'#markup' => $this->_build_html($list),
			'#attached' => array(
				'library' =>  array(
					'cp_contacts/style',
					'cp_contacts/script'
				),
			),
		);
	}
	
	function _build_html($list) {
		
		$output = '<div id="cp_contacts">';
		
		$url = $GLOBALS['base_url'] . '/' . PublicStream::basePath() . '/';
		
		$co = 1;
		
		foreach ($list as $c) {
			
			$photo = $url . str_replace('public://', '', $c->getPhoto());
			$output .= '<div class="contact row-' . $co . '">';
			
			$output .= '<div class="region-left">';
			$output .= '<img src="' . $photo . '" width="100" height="100" alt="" />';
			$output .= '</div>';
			
			$output .= '<div class="region-right">';
			$output .= '<div class="name">' . $c->getName() . '</div>';
			$output .= '<div class="title">' . $c->getTitle() . '</div>';
			$output .= '<div class="organization">' . $c->getOrganization() . '</div>';
			$output .= '<div class="email"><a href="mailto:' . $c->getEmail() . '">' . $c->getEmail() . '</a></div>';
			$output .= '<div class="phone"><a href="tel:' . $c->getPhone() . '">' . $c->getPhone() . '</a></div>';
			$output .= '</div>';
			
			$output .= '</div>';
			
			$co ++;
			if ($co == 4) { $co = 1; }
		}
		
		$output .= '</div>';
		
		return $output;
	}
	
	function _prepare_contacts() {
		
		$list = array();
		
		foreach ($this->_collect_contacts() as $c) {
			$c = $this->_add_email($c);
			$c = $this->_add_phone($c);
			$c = $this->_add_photo($c);
			$c = $this->_add_organization($c);
			$c = $this->_add_title($c);
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
			select entity_id, field_cp_contact_name_value	
			from {node__field_cp_contact_name}
			'
		
		)->fetchAll();
		
		
		foreach ($result as $record) {
			if ($record) {
				$contact = new Contact();
				$contact->setId($record->entity_id);
				$contact->setName($record->field_cp_contact_name_value);
				
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