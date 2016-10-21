<?php

namespace Drupal\cp_files\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_files\CPFiles\SortedListOfFiles;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StreamWrapper\PublicStream;

/**
 * @Block(
 *   id = "cp_movies",
 *   admin_label = @Translation("CP Movie"),
 * )
 */
class CpMovie extends BlockBase {
	
	function build() {
		$listOfFiles = new SortedListOfFiles();
		$list = $listOfFiles->getListLatestFirst();
		
		$config = $this->getConfiguration();
		$category = $config['cp_files_movie_category'];
		
		$list_of_elements = array();
		
		if ($list != null) {
				
			foreach ($list as $filesObj) {
		
				if ($category == $filesObj->getCategory()) {
					
					if (count($filesObj->getFiles()) > 0) {
						
						$movie = $filesObj->getFiles()[0];
						
						if($movie->getUri() != null
								&& $movie->getMime() == 'video/mp4') {
									
							$url = '/' . PublicStream::basePath() . '/';
						
							$uri = $url . str_replace('public://', '', $movie->getUri());
							$movie->setUri($uri);
							
							$list_of_elements['CP_MOVIE']['TITLE'] = $filesObj->getTitle();
						
							$list_of_elements['CP_MOVIE']['BODY'] = $filesObj->getBody();
						
							if ($filesObj->getPictureUri() != null) {
								$picture_uri = $url . str_replace('public://', '', $filesObj->getPictureUri());
								$list_of_elements['CP_MOVIE']['PICTURE'] = $picture_uri;
							}
						
							$list_of_elements['CP_MOVIE']['ELEMENT'] = $movie;
						
						}
						
						break;	
					}
				}
			}
		}		
		
		return array(
			'#theme' => 'cp_movie',
			'#elements' => $list_of_elements,
			'#attached' => array(
				'library' =>  array(
					'cp_files/style',
					'cp_files/script'
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
 		$listOfFiles = new SortedListOfFiles();
 		$list = $listOfFiles->getListLatestFirst();
 		foreach ($list as $file) {
 			$category_options[$file->getCategory()] = $file->getCategory();
 		}
		
		$category = '';
		if (isset($config['cp_files_movie_category'])) {
			$category = $config['cp_files_movie_category'];
		}
		
		if (empty($category_options)) {
			$category_options['MOVIE'] = 'MOVIE';
		}
		
		$form['cp_files_movie_category'] = array (
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
		$this->setConfigurationValue('cp_files_movie_category', $form_state->getValue('cp_files_movie_category'));
	}	
}