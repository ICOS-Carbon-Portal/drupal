<?php

namespace Drupal\cp_media\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_documents\CPDocuments\SortedListOfDocuments;
use Drupal\cp_pictures\CPPictures\SortedListOfPictures;
use Drupal\cp_movies\CPMovies\SortedListOfMovies;
use Drupal\cp_files\CPFiles\SortedListOfFiles;
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
		
		$listOfPictures = new SortedListOfPictures();
		$pictures = $listOfPictures->getListLatestFirst();
		
		$listOfMovies = new SortedListOfMovies();
		$movies = $listOfMovies->getListLatestFirst();
		
		$config = $this->getConfiguration();
		$category = $config['cp_media_category'];
		
		$list_of_elements = array();
		
		if ($movies != null) {
				
			foreach ($movies as $movie) {
		
				if ($movie->getHistorical() == '0'
						&& $category == $movie->getCategory()
						&& $movie->getMovieUri() != null) {
					
					$url = '/' . PublicStream::basePath() . '/';
					
					$movie_uri = $url . str_replace('public://', '', $movie->getMovieUri());
					$movie->setMovieUri($movie_uri);
					$list_of_elements['CP_MEDIA']['MOVIE'] = 'MOVIE';
					
					if ($movie->getBody() == null) { $movie->setBody(''); }
					
					if ($movie->getPictureUri() != null) {
						$picture_uri = $url . str_replace('public://', '', $movie->getPictureUri());
						$movie->setPictureUri($picture_uri);	
						$list_of_elements['CP_MEDIA']['PICTURE'] = 'picture';
					}
					
					$list_of_elements['CP_MEDIA']['ELEMENT'] = $movie;
					
					break;
				}
			}
		}		
		
		return array(
			'#theme' => 'cp_media_latest',
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
		
		/**
		// Documents
		$listOfDocuments = new SortedListOfDocuments();
		$list = $listOfDocuments->getListLatestFirst();
		foreach ($list as $element) {
			$category_options[$element->getCategory()] = $element->getCategory();
		}
		$list = null;
		**/
		
		// Pictures
		$listOfPictures = new SortedListOfPictures();
		$list = $listOfPictures->getListLatestFirst();
		foreach ($list as $element) {
			$category_options[$element->getCategory()] = $element->getCategory();
		}
		$list = null;
		
		// Movies
 		$listOfMovies = new SortedListOfMovies();
 		$list = $listOfMovies->getListLatestFirst();
 		foreach ($list as $element) {
			$category_options[$element->getCategory()] = $element->getCategory();
		}
		$list = null;
		
		/**
		// Files
		$listOfFiles = new SortedListOfFiles();
		$list = $listOfFiles->getListLatestFirst();
		foreach ($list as $element) {
			$category_options[$element->getCategory()] = $element->getCategory();
		}
		$list = null;
		**/
		
		$category = '';
		if (isset($config['cp_media_category'])) {
			$category = $config['cp_media_category'];
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