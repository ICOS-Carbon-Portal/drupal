<?php

namespace Drupal\cp_files\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_files\CPFiles\SortedListOfFiles;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *   id = "cp_files",
 *   admin_label = @Translation("CP Files"),
 * )
 */
class CpFiles extends BlockBase {
	
	function build() {
		$listOfFiles = new SortedListOfFiles();
		$list = $listOfFiles->getListLatestFirst();
		
		return array(
			'#markup' => $this->_build_html($list),
			'#attached' => array(
				'library' =>  array(
					'cp_files/style',
					'cp_files/script'
				),
			),
		);
		
	}
	
	
	function _build_html($list) {
		$config = $this->getConfiguration();
		
		$id = $config['cp_files_id'];
		$id = str_replace(' ', '_', $id);
		$id = str_replace('.', '_', $id);
		
		$category_name = $config['cp_files_category_name'];	
		$category_icon = $config['cp_files_category_icon'];
		
		$path = drupal_get_path('module', 'cp_files');
		
		$output = '<div class="cp_files">';
		
		$output .= '<div id="cp_files_accordion_' . $id . '" class="panel-group" role="tablist" aria-multiselectable="true">';
		$output .= '<div class="panel panel-default">';
		$output .= '<div id="cp_files_accordion_' . $id . '_heading" class="panel-heading" role="tab">';
		$output .= '<h3 class="panel-title">';
		$output .= '<a role="button" data-toggle="collapse" data-parent="#cp_files_accordion_' . $id . '" href="#cp_files_accordion_' . $id . '_collapse" aria-expanded="true" aria-controls="cp_files_accordion_' . $id . '_collapse">';
		$output .= $category_name;
		$output .= '</a>';
		$output .= '<img src="/' . $path . '/images/' . $category_icon . '.svg" />';
		$output .= '</h3>';
		$output .= '</div>';
		$output .= '<div id="cp_files_accordion_' . $id . '_collapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="cp_files_accordion_' . $id . '_heading">';
		$output .= '<div class="panel-body">';
		
		if ($list != null) {
			
			foreach ($list as $files) {
				
				if ($category_name == $files->getCategory()) {
				
						
					foreach ($files->getFiles() as $file) {
						
						if ($file->getUri() != null
								&& $file->getUri() != '') {
			
							$url = '/' . PublicStream::basePath() . '/';				
							$file_url = $url . str_replace('public://', '', $file->getUri());	
							$file_description = '';
							if ($file->getDescription() != null && $file->getDescription() != '') { 
								$file_description = $file->getDescription(); 
							}
								
							$output .= '<div class="cp_file">';
							
							$output .= '<div>';
							
							$output .= '<h4 class="file">';
							$output .= '<a href="' . $file_url . '" title="' . $file->getName() . '" >' . $file->getName(). '</a>';
							$output .= '</h4>';
							$output .= '</div>';
							
							$output .= '<div class="description">';
							$output .= $file_description;
							$output .= '</div>';
							
							$output .= '</div>';
						}
					}							
				}				
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
		
		$form = parent::blockForm($form, $form_state);
	
		$id = microtime();
		if (isset($config['cp_files_id'])) {
			$id = $config['cp_files_id'];
		}
		
		$form['cp_files_id'] = array (
				'#type' => 'hidden',
				'#value' => $id
		);
		
		
		$category_options = array();
 		$listOfFiles = new SortedListOfFiles();
 		$list = $listOfFiles->getListLatestFirst();
 		foreach ($list as $files) {
 			$category_options[$files->getCategory()] = $files->getCategory();
 		}
		
		$category_name = '';
		if (isset($config['cp_files_category_name'])) {
			$category_name = $config['cp_files_category_name'];
		}
		
		if (empty($category_options)) {
			$category_options['FILES'] = 'FILES';
		}
		
		$form['cp_files_category_name'] = array (
				'#type' => 'select',
				'#title' => $this->t('Select a category'),
				'#description' => $this->t(''),
				'#options' => $category_options,
				'#default_value' => $category_name
		);
		
		
		$category_icon = 'icon_none';
		if (isset($config['cp_files_category_icon'])) {
			$category_icon = $config['cp_files_category_icon'];
		}
		
		$path = drupal_get_path('module', 'cp_files');
		
		$icon_options = array();
		$icons = array('icon_none', 'icon_brochures', 'icon_news', 'icon_press', 'icon_publications', 'icon_releases');
		
		foreach ($icons as $ic) {		
			if ($ic != 'icon_none') {
				$icon_options[$ic] = '<img src="/' . $path . '/images/' . $ic . '_thumb.png" /><hr />';
				
			} else {
				$icon_options[$ic] = '<span>None icon</span><hr />';
			}
			
		}
		
		$form['cp_files_category_icon'] = array(
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
		$this->setConfigurationValue('cp_files_id', $form_state->getValue('cp_files_id'));
		$this->setConfigurationValue('cp_files_category_name', $form_state->getValue('cp_files_category_name'));
		$this->setConfigurationValue('cp_files_category_icon', $form_state->getValue('cp_files_category_icon'));
	}	
}