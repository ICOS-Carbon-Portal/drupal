<?php

namespace Drupal\cp_documents\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_documents\CPDocuments\SortedListOfDocuments;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *   id = "cp_documents",
 *   admin_label = @Translation("CP Documents"),
 * )
 */
class CpDocuments extends BlockBase {
	
	function build() {
		$listOfDocuments = new SortedListOfDocuments();
		$list = $listOfDocuments->getListLatestFirst();
		
		if ($list != null) {
		
			return array(
				'#markup' => $this->_build_html($list),
				'#attached' => array(
					'library' =>  array(
						'cp_documents/style',
						'cp_documents/script'
					),
				),
			);
		
		} else {
			return array('#markup' => '',);
		}
	}
	
	
	function _build_html($documents) {
		$config = $this->getConfiguration();
		
		$cp_document_id = '';
		if (isset($config['cp_documents_id'])) {
			$cp_document_id = $config['cp_documents_id'];
		}
		$cp_document_id = str_replace(' ', '_', $cp_document_id);
		$cp_document_id = str_replace('.', '_', $cp_document_id);
		
		$category_key = '0';
		if (isset($config['cp_documents_category_key'])) {
			$category_key = $config['cp_documents_category_key'];
		}
		
		$category_name = 'Open';
		if (isset($config['cp_documents_category_name']) && $config['cp_documents_category_name'] != '') {
			$category_name = $config['cp_documents_category_name'];
		}
		
		$category_icon = '';
		if (isset($config['cp_documents_category_icon'])) {
			$category_icon = $config['cp_documents_category_icon'];
		}
		
		$path = drupal_get_path('module', 'cp_documents');
		
		
		$output = '<div class="cp_documents">';
		
		$output .= '<div id="cp_document_accordion_' . $cp_document_id . '" class="panel-group" role="tablist" aria-multiselectable="true">';
		$output .= '<div class="panel panel-default">';
		$output .= '<div id="cp_document_accordion_' . $cp_document_id . '_heading" class="panel-heading" role="tab">';
		$output .= '<h3 class="panel-title">';
		$output .= '<img src="/' . $path . '/images/' . $category_icon . '.svg" />';
		$output .= '<a role="button" data-toggle="collapse" data-parent="#cp_document_accordion_' . $cp_document_id . '" href="#cp_document_accordion_' . $cp_document_id . '_collapse" aria-expanded="true" aria-controls="cp_document_accordion_' . $cp_document_id . '_collapse">';
		$output .= $category_name;
		$output .= '</a>';
		$output .= '</h3>';
		$output .= '</div>';
		$output .= '<div id="cp_document_accordion_' . $cp_document_id . '_collapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="cp_document_accordion_' . $cp_document_id . '_heading">';
		$output .= '<div class="panel-body">';
		
		
		foreach ($documents as $document) {
			
			if ($category_key == $document->getCategoryKey()
					&& $document->getFileUrl() != null
					&& $document->getFileUrl() != '') {
				
				
				$url = $GLOBALS['base_url'] . '/' . PublicStream::basePath() . '/';				
				$file_url = $url . str_replace('public://', '', $document->getFileUrl());	
				$file_description = '';
				if ($document->getFileDescription() != null || $document->getFileDescription() != '') { 
					$file_description = $document->getFileDescription(); 
				}
					
				
				$icon = '';
				if ($document->getPictureUrl() != null || $document->getPictureUrl() != '') {
					$picture_url = $url . str_replace('public://', '', $document->getPictureUrl());
					$icon = '<img src="' . $picture_url . '" />';	
				}
				
				$output .= '<div>';
				
				$output .= '<div class="icon">';
				$output .= $icon;
				$output .= '</div>';
				
				
				$output .= '<div class="file">';
				$output .= '<h4>';
				$output .= '<a href="' . $file_url . '" title="' . $document->getTitle() . '" >' . $document->getTitle(). '</a>';
				$output .= '</h4>';
				$output .= '<span>';
				$output .= $file_description;
				$output .= '</span>';
				$output .= '</div>';
				
				$output .= '</div>';
						
			}	
			
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
	
		$cp_document_id = microtime();
		if (isset($config['cp_documents_id'])) {
			$cp_document_id = $config['cp_documents_id'];
		}
		
		$category_key = '0';
		if (isset($config['cp_documents_category_key'])) {
			$category_key = $config['cp_documents_category_key'];
		}
		
		$category_name = '';
		if (isset($config['cp_documents_category_name'])) {
			$category_name = $config['cp_documents_category_name'];
		}
		
		$category_icon = 'icon_none';
		if (isset($config['cp_documents_category_icon'])) {
			$category_icon = $config['cp_documents_category_icon'];
		}
	
		$form = parent::blockForm($form, $form_state);
	
		$form['cp_documents_id'] = array (
				'#type' => 'hidden',
				'#value' => $cp_document_id
		);
		
		$form['cp_documents_category_key'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('Type the category key value'),
				'#description' => $this->t(''),
				'#default_value' => $category_key
		);
		
		$form['cp_documents_category_name'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('Type the category name'),
				'#description' => $this->t(''),
				'#default_value' => $category_name
		);
		
		
		$path = drupal_get_path('module', 'cp_documents');
		
		$icon_options = array();
		$icons = array('icon_none', 'icon_brochures', 'icon_news', 'icon_press', 'icon_publications', 'icon_releases');
		
		foreach ($icons as $ic) {		
			if ($ic != 'icon_none') {
				$icon_options[$ic] = '<img src="/' . $path . '/images/' . $ic . '_thumb.png" /><hr />';
				
			} else {
				$icon_options[$ic] = '<span>None icon</span><hr />';
			}
			
		}
		
		$form['cp_documents_category_icon'] = array(
				'#type' => 'radios',
				'#title' => $this->t('Select an icon'),
				'#description' => t(''),
				'#options' => $icon_options,
				'#default_value' => $category_icon,
				'#prefix' => '<div>',
				'#suffix' => '</div>'
		);		
		
		return $form;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function blockSubmit($form, FormStateInterface $form_state) {
		$this->setConfigurationValue('cp_documents_id', $form_state->getValue('cp_documents_id'));
		$this->setConfigurationValue('cp_documents_category_key', $form_state->getValue('cp_documents_category_key'));
		$this->setConfigurationValue('cp_documents_category_name', $form_state->getValue('cp_documents_category_name'));
		$this->setConfigurationValue('cp_documents_category_icon', $form_state->getValue('cp_documents_category_icon'));
	}	
}