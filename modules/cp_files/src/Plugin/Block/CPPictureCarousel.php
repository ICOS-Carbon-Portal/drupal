<?php

namespace Drupal\cp_files\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_files\CPFiles\SortedListOfFiles;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StreamWrapper\PublicStream;

/**
 * @Block(
 *   id = "cp_picture_carousel",
 *   admin_label = @Translation("CP Picture Carousel"),
 * )
 */
class CpPictureCarousel extends BlockBase {
	
	function build() {
		$listOfFiles = new SortedListOfFiles();
		$list = $listOfFiles->getListLatestFirst();
		
		$config = $this->getConfiguration();
		$category = $config['cp_files_picture_carousel_category'];
		
		if ($list != null) {
		
			return array(
				'#markup' => $this->_build_html($list, $category),
				'#attached' => array(
					'library' =>  array(
						'cp_pictures/style',
						'cp_pictures/script'
					),
				),
			);
		
		} else {
			return array('#markup' => '',);
		}
	}
	
	function _build_html($list, $category) {
	
		$url = '/' . PublicStream::basePath() . '/';
		
		$output = '<div id="cp_pictures_carousel" class="carousel slide" data-ride="carousel" data-interval="5000">';
		
		$output .= '<div class="control_left">';
		$output .= '<a class="left carousel-control" href="#cp_pictures_carousel" role="button" data-slide="prev" title="Previous">';
		$output .= '<span class="" aria-hidden="true">Prev</span>';
 		$output .= '<span class="sr-only">Previous</span>';
		$output .= '</a>';
		$output .= '</div>';

		$output .= '<div class="control_right">';
		$output .= '<a class="right carousel-control" href="#cp_pictures_carousel" role="button" data-slide="next" title="Next">';
		$output .= '<span class="" aria-hidden="true">Next</span>';
		$output .= '<span class="sr-only">Next</span>';
		$output .= '</a>';
		$output .= '</div>';
		
		$output .= '<div class="carousel-inner vertical" role="listbox">';
		
		foreach ($list as $filesObj) {
		
			if ($filesObj->getCategory() == $category) {
					
				if (count($filesObj->getFiles()) > 0) {
					
					$pictures = $filesObj->getFiles();
					$co = 0;
					foreach ($pictures as $picture) {
						
						$picture_uri = $url . str_replace('public://', '', $picture->getUri());
						$picture_title = '';
// 						if ($picture->getDescription() != null && $picture->getDescription() != '') {
// 							$picture_title = $picture->getDescription();
// 						}
						
						if ($co == 0) {
							$output .= '<div class="item active">';
							$co ++;
						
						} else {
							$output .= '<div class="item">';
						}
						
						$output .= '<img src="' . $picture_uri . '" title="' . $picture_title . '" />';
						
						$output .= '</div>';
						
					}	
				}
			}
		}
		
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
	
		$category_options = array();
		$listOfFiles = new SortedListOfFiles();
		$list = $listOfFiles->getListLatestFirst();
		foreach ($list as $file) {
			$category_options[$file->getCategory()] = $file->getCategory();
		}
	
		$category = '';
		if (isset($config['cp_files_picture_carousel_category'])) {
			$category = $config['cp_files_picture_carousel_category'];
		}
	
		if (empty($category_options)) {
			$category_options['PICTURE-CAROUSEL'] = 'CAROUSEL';
		}
	
		$form['cp_files_picture_carousel_category'] = array (
				'#type' => 'select',
				'#title' => $this->t('Select a category'),
				'#description' => $this->t(''),
				'#options' => $category_options,
				'#default_value' => $category
		);
	
		return $form;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function blockSubmit($form, FormStateInterface $form_state) {
		$this->setConfigurationValue('cp_files_picture_carousel_category', $form_state->getValue('cp_files_picture_carousel_category'));
	}	
}
