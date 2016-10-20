<?php

namespace Drupal\cp_pictures\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_pictures\CPPictures\SortedListOfPictures;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StreamWrapper\PublicStream;

/**
 * @Block(
 *   id = "cp_pictures_carousel",
 *   admin_label = @Translation("CP Picture Carousel"),
 * )
 */
class CpPicturesCarousel extends BlockBase {
	
	function build() {
		$listOfPictures = new SortedListOfPictures();
		$list = $listOfPictures->getListLatestFirst();
		
		if ($list != null) {
		
			return array(
				'#markup' => $this->_build_html($list),
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
	
	function _build_html($pictures) {
	
		$config = $this->getConfiguration();
		$category = $config['cp_pictures_category'];
		
		$max_width = 0;
		$max_height = 0;
		foreach ($pictures as $picture) {
			if ($picture->getPictureWidth() > $max_width) {$max_width = $picture->getPictureWidth(); }
			if ($picture->getPictureHeight() > $max_height) {$max_height = $picture->getPictureHeight(); }
		}
		
		
		$output = '<div id="cp_pictures_carousel" class="carousel slide" data-ride="carousel" data-interval="5000">';
		
		
// 		$output .= '<div class="control_left">';
// 		$output .= '<a class="left carousel-control" href="#cp_pictures_carousel" role="button" data-slide="prev" title="Previous">';
// 		$output .= '<span class="" aria-hidden="true">Prev</span>';
//  	$output .= '<span class="sr-only">Previous</span>';
// 		$output .= '</a>';
// 		$output .= '</div>';

// 		$output .= '<div class="control_right">';
// 		$output .= '<a class="right carousel-control" href="#cp_pictures_carousel" role="button" data-slide="next" title="Next">';
// 		$output .= '<span class="" aria-hidden="true">Next</span>';
// 		$output .= '<span class="sr-only">Next</span>';
// 		$output .= '</a>';
// 		$output .= '</div>';
		
		
		$output .= '<div class="carousel-inner vertical" role="listbox">';
		
		
		$url = '/' . PublicStream::basePath() . '/';

		$co = 0;
		foreach ($pictures as $picture) {
			
			if ($picture->getCategory() == $category) {
					
				$picture_uri = $url . str_replace('public://', '', $picture->getPictureUri());
				$picture_title = '';
				if ($picture->getPictureDescription() != null && $picture->getPictureDescription() != '') { 
					$picture_title = $picture->getPictureDescription(); 
				}
				
				
				if ($picture->getPictureWidth() < $max_width || $picture->getPictureHeight() < $max_height) {
					$new_file = 'sites/default/files/new_'. $picture->getPictureName();
					
					$im = imagecreatetruecolor($max_width, $max_height);
					$bgc = imagecolorallocate($im, 255, 255, 255);
					imagefilledrectangle($im, 0, 0, $max_width, $max_height, $bgc);
					imagejpeg($im, $new_file);
					
					$dst_im = imagecreatefromjpeg($new_file);
					$src_im = imagecreatefromjpeg('sites/default/files/' . str_replace('public://', '', $picture->getPictureUri()));
					$dst_x = ($max_width - $picture->getPictureWidth()) / 2;
					$dst_y = 0; //($max_height - $picture->getPictureHeight()) / 2;
					$src_x = 0;
					$src_y = 0;
					$src_w = $picture->getPictureWidth();;
					$src_h = $picture->getPictureHeight();
					$pct = 100;
					
					imagecopymerge($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct);				
					imagejpeg($dst_im, $new_file);	
					$picture_uri = $url.'new_'. $picture->getPictureName();
					
				}
				
				if ($co === 0) {
					$output .= '<div class="item active">';
					$co ++;
				
				} else {
					$output .= '<div class="item">';
				}
				
				
				$output .= '<img src="' . $picture_uri . '" title="' . $picture_title . '" />';
				
				$output .= '</div>';	
		
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
		$listOfPictures = new SortedListOfPictures();
		$list = $listOfPictures->getListLatestFirst();
		foreach ($list as $picture) {
			$category_options[$picture->getCategory()] = $picture->getCategory();
		}
	
		$category = '';
		if (isset($config['cp_pictures_category'])) {
			$category = $config['cp_pictures_category'];
		}
	
		if (empty($category_options)) {
			$category_options['PICTURE'] = 'PICTURE';
		}
	
		$form['cp_pictures_category'] = array (
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
		$this->setConfigurationValue('cp_pictures_category', $form_state->getValue('cp_pictures_category'));
	}	
}