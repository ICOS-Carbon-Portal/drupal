<?php

namespace Drupal\cp_movies\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_movies\CPMovies\SortedListOfMovies;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StreamWrapper\PublicStream;

/**
 * @Block(
 *   id = "cp_movies_latest",
 *   admin_label = @Translation("Latest CP Movie"),
 * )
 */
class CpMoviesLatest extends BlockBase {
	
	function build() {
		$listOfMovies = new SortedListOfMovies();
		$list = $listOfMovies->getListLatestFirst();
		
		$config = $this->getConfiguration();
		$category = $config['cp_movies_category'];
		
		$list_of_elements = array();
		
		if ($list != null) {
				
			foreach ($list as $movie) {
		
				if ($movie->getHistorical() == '0'
						&& $category == $movie->getCategory()
						&& $movie->getMovieUri() != null) {
					
					$url = '/' . PublicStream::basePath() . '/';
					
					$movie_uri = $url . str_replace('public://', '', $movie->getMovieUri());
					$movie->setMovieUri($movie_uri);
					$list_of_elements['CP_MOVIES']['MOVIE'] = 'MOVIE';
					
					if ($movie->getBody() == null) { $movie->setBody(''); }
					
					if ($movie->getPictureUri() != null) {
						$picture_uri = $url . str_replace('public://', '', $movie->getPictureUri());
						$movie->setPictureUri($picture_uri);	
						$list_of_elements['CP_MOVIES']['PICTURE'] = 'picture';
					}
					
					$list_of_elements['CP_MOVIES']['ELEMENT'] = $movie;
					
					break;
				}
			}
		}		
		
		return array(
			'#theme' => 'cp_movies_latest',
			'#elements' => $list_of_elements,
			'#attached' => array(
				'library' =>  array(
					'cp_movies/style',
					'cp_movies/script'
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
 		$listOfMovies = new SortedListOfMovies();
 		$list = $listOfMovies->getListLatestFirst();
 		foreach ($list as $movie) {
 			$category_options[$movie->getCategory()] = $movie->getCategory();
 		}
		
		$category = '';
		if (isset($config['cp_movies_category'])) {
			$category = $config['cp_movies_category'];
		}
		
		if (empty($category_options)) {
			$category_options['MOVIE'] = 'MOVIE';
		}
		
		$form['cp_movies_category'] = array (
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
		$this->setConfigurationValue('cp_movies_category', $form_state->getValue('cp_movies_category'));
	}	
}