<?php

namespace Drupal\cp_cmis\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\cp_cmis\CMIS\Service;
use Drupal\cp_cmis\CMIS\CMISIcons;


/**
 * @Block(
 *   id = "cp_cmis_documents",
 *   admin_label = @Translation("CP CMIS documents"),
 * )
 */
class CpCMISDocuments extends BlockBase implements BlockPluginInterface {
	
	private $service;

	/**
	 * {@inheritdoc}
	 */
	public function build() {
		
		return array(
			'#markup' => $this->_build_html(),
			'#attached' => array(
					'library' =>  array(
							'cp_cmis/style_documents',
							'cp_cmis/script_documents'
					),
			),
		);
	}
	
	
	function _build_html() {
		$config = $this->getConfiguration();
		
		$id = $config['cp_cmis_documents_id'];
		$id = str_replace(' ', '_', $id);
		$id = str_replace('.', '_', $id);
		
		$title = $config['cp_cmis_documents_title'];
		$icon = $config['cp_cmis_documents_icon'];
			
		$path = drupal_get_path('module', 'cp_cmis');
		
		$this->service = new Service();
		$list = $this->service->getDocuments( $config['cp_cmis_documents_url'] );
		
		$output = '<div class="cp_cmis_documents">';
		
		$output .= '<div id="cp_cmis_document_accordion_' . $id . '" class="panel-group" role="tablist" aria-multiselectable="true">';
		$output .= '<div class="panel panel-default">';
		$output .= '<div id="cp_cmis_document_accordion_' . $id . '_heading" class="panel-heading" role="tab">';
		$output .= '<h3 class="panel-title">';
		$output .= '<img src="/' . $path . '/icons/' . $icon . '.svg" />';
		$output .= '<a role="button" data-toggle="collapse" data-parent="#cp_cmis_document_accordion_' . $id . '" href="#cp_cmis_document_accordion_' . $id . '_collapse" aria-expanded="true" aria-controls="cp_cmis_document_accordion_' . $id . '_collapse">';
		$output .= $title;
		$output .= '</a>';
		$output .= '</h3>';
		$output .= '</div>';
		$output .= '<div id="cp_cmis_document_accordion_' . $id . '_collapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="cp_cmis_document_accordion_' . $id . '_heading">';
		$output .= '<div class="panel-body">';
		
		foreach ($list as $document) {
		
			$lastMastModifiedDate = new \DateTime($document->getLastModifiedDate());
			$type = substr($document->getName(), strripos($document->getName(), '.') + 1);
		
			$output .= '<div class="cp_cmis_document">';
			
			$output .= '<div>';	
			$output .= '<div class="icon">';
			$output .= '<img src="' . CMISIcons::getIcon($type) . '" />';
			$output .= '</div>';
			
			$output .= '<h4 class="file">';
			$output .= '<a href="/fetch/'. $document->getId() .'">';
			$output .= $document->getName();
			$output .= '</a>';
			$output .= '</h4>';	
			$output .= '</div>';
			
			$output .= '<div class="description">';
			$output .= $document->getDescription();
			$output .= '</div>';
			
			$output .= '<div>';		
			$output .= '<div class="modified_date">' . $lastMastModifiedDate->format('Y-m-d H:i') . '</div>';
			$output .= '<div class="modified_by">' . $document->getLastModifiedBy() . '</div>';		
			$output .= '</div>';
			
			$output .= '</div>';
		}
		
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		
		$output .= '</div>';
		
		return $output;
	}
	

	/**
	 * {@inheritdoc}
	 */
	public function blockForm($form, FormStateInterface $form_state) {
		$config = $this->getConfiguration();
	
		$form = parent::blockForm($form, $form_state);
	
		$id = microtime();
		if (isset($config['cp_cmis_documents_id'])) {
			$id = $config['cp_cmis_documents_id'];
		}
	
		$form['cp_cmis_documents_id'] = array (
				'#type' => 'hidden',
				'#value' => $id
		);
	
	
		$title = '';
		if ($config['cp_cmis_documents_title'] != null) {
			$title = $config['cp_cmis_documents_title'];
		}
		
		$form['cp_cmis_documents_title'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('Title'),
				'#default_value' => $title
		);
		
		
		$url = '';
		if ($config['cp_cmis_documents_url'] != null) {
			$url = $config['cp_cmis_documents_url'];
		}
		
		$form['cp_cmis_documents_url'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('URL to the external CMIS folder'),
				'#default_value' => $url
		);
	
	
		$icon = 'icon_none';
		if (isset($config['cp_cmis_documents_icon'])) {
			$icon = $config['cp_cmis_documents_icon'];
		}
	
		$path = drupal_get_path('module', 'cp_cmis');
		$icon_options = array();
		$icons = array('icon_none', 'icon_atc', 'icon_cal', 'icon_cp', 'icon_etc', 'icon_otc');
	
		foreach ($icons as $ic) {
			if ($ic != 'icon_none') {
				$icon_options[$ic] = '<img src="/' . $path . '/icons/' . $ic . '_thumb.png" /><hr />';
	
			} else {
				$icon_options[$ic] = '<span>None icon</span><hr />';
			}
				
		}
	
		$form['cp_cmis_documents_icon'] = array(
				'#type' => 'radios',
				'#title' => $this->t('Select an icon'),
				'#description' => t(''),
				'#options' => $icon_options,
				'#default_value' => $icon,
				'#prefix' => '<div>',
				'#suffix' => '</div>'
		);
	
		return $form;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function blockSubmit($form, FormStateInterface $form_state) {
		$this->setConfigurationValue('cp_cmis_documents_id', $form_state->getValue('cp_cmis_documents_id'));
		$this->setConfigurationValue('cp_cmis_documents_title', $form_state->getValue('cp_cmis_documents_title'));
		$this->setConfigurationValue('cp_cmis_documents_url', $form_state->getValue('cp_cmis_documents_url'));
		$this->setConfigurationValue('cp_cmis_documents_icon', $form_state->getValue('cp_cmis_documents_icon'));
	}

}