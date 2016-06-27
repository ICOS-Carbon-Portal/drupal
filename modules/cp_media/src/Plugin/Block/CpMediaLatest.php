<?php

namespace Drupal\cp_media\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_media\CPMedia\SortedListOfMedia;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StreamWrapper\PublicStream;

/**
 * @Block(
 *   id = "cp_media_latest",
 *   admin_label = @Translation("Latest CP Media"),
 * )
 */
class CpMediaLatest extends BlockBase {
	
	function build() {
		$listOfMedia = new SortedListOfMedia();
		$list = $listOfMedia->getListLatestFirst();
		
		$config = $this->getConfiguration();
		$category = $config['cp_media_category'];
		
		$list_of_elements = array();
		
		if ($list != null) {
				
			foreach ($list as $media) {
		
				if ($media->getHistorical() == '0' && $category == $media->getCategory()) {
					$has_media = false;
					
					$url = '/' . PublicStream::basePath() . '/';
					
					if ($media->getPictureUri() != null) {
						$picture_uri = $url . str_replace('public://', '', $media->getPictureUri());
						$media->setPictureUri($picture_uri);	
						$list_of_elements['CP_MEDIA']['PICTURE'] = 'picture';
						$has_media = true;
					}
					
					if ($media->getVideoUri() != null) { 
						$video_uri = $url . str_replace('public://', '', $media->getVideoUri());
						$media->setVideoUri($video_uri);
						$list_of_elements['CP_MEDIA']['VIDEO'] = 'video';
						$has_media = true;
					}
					
					if ($media->getAudioUri() != null) {
						$audio_uri = $url . str_replace('public://', '', $media->getAudioUri());
						$media->setAudioUri($audio_uri);
						$list_of_elements['CP_MEDIA']['AUDIO'] = 'audio';
						$has_media = true;
					}
					
					if ($has_media) {
						$list_of_elements['CP_MEDIA']['ELEMENT'] = $media;
					}
					
					break;
				}
			}
		}		
		
		return array(
			'#theme' => 'cp_media',
			'#elements' => $list_of_elements,
			'#attached' => array(
				'library' =>  array(
					'cp_media/style',
					'cp_media/script'
				),
			),
		);	
	}
	
	
	/**
	 * {@inheritdoc}
	 */
	public function blockForm($form, FormStateInterface $form_state) {
		$config = $this->getConfiguration();
		
		$form = parent::blockForm($form, $form_state);
	
		$category_options = array();
 		$listOfMedia = new SortedListOfMedia();
 		$list = $listOfMedia->getListLatestFirst();
 		foreach ($list as $media) {
 			$category_options[$media->getCategory()] = $media->getCategory();
 		}
		
		$category = '';
		if (isset($config['cp_media_category'])) {
			$category_name = $config['cp_media_category'];
		}
		
		if (empty($category_options)) {
			$category_options['MEDIA'] = 'MEDIA';
		}
		
		$form['cp_media_category'] = array (
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
		$this->setConfigurationValue('cp_media_category', $form_state->getValue('cp_media_category'));
	}	
}