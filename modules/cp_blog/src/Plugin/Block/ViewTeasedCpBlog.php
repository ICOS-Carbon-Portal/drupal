<?php

namespace Drupal\cp_blogs\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_blogs\CPBlogs\SortedListOfBlogs;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *   id = "view_teased_cp_blog",
 *   admin_label = @Translation("View teased CP blog"),
 * )
 */
class ViewTeasedCpBlog extends BlockBase {
	
	function build() {
		$listOfBlogs = new SortedListOfBlogs();
		$list = $listOfBlogs->getListLatestFirst();
		
		if ($list != null) {
		
			return array(
				'#markup' => $this->_build_html($list),
				'#attached' => array(
					'library' =>  array(
						'cp_blogs/style',
						'cp_blogs/script'
					),
				),
				'#cache' => array(
						'max-age' => 1
				),
			);
		
		} else {
			return array('#markup' => '',);
		}
	}
	
	function _build_html($list) {
		$config = $this->getConfiguration();
		
		$blog_category = '';
		if (isset($config['cp_blog_blog_category'])) {
			$blog_category = $config['cp_blog_blog_category'];
		}
		
		$output = '';
		
		foreach ($list as $blog) {
			
			if ($blog->getCategory() == $blog_category && $blog->getHistorical() == 0) {
				
				$output .= '<div class="cp_blog_teased">';
				
				$date_format = 'Y-m-d';
				if (isset($config['cp_blog_date_format'])) {
					if ($config['cp_blog_date_format'] == 'day-month-year') { $date_format = 'd-m-Y'; }
				}
				
				$output .= '<div class="date">' . date($date_format, $blog->getChanged()) . '</div>';
				
				$output .= '<div class="heading">' . $blog->getTitle() . '</div>';
						
				if ($blog->getPictureUri() != null || $blog->getPictureUri() != '') {
					$url = '/' . PublicStream::basePath() . '/';
					$picture_url = $url . str_replace('public://', '', $blog->getPictureUri());
						
					$picture_title = '';
					if ($blog->getPictureTitle() != null || $blog->getPictureTitle() != '') { $picture_title = $blog->getPictureTitle(); }
					
					$output .= '<div class="picture">';
					$output .= '<img src="' . $picture_url . '" alt="' . $picture_title . '" title="' . $picture_title . '" />';
					$output .= '</div>';
				}
				
				
				if (strlen($blog->getText()) > 1 ) {
					
					$max_stop = 160;
					$start = 0;
					$stop = $max_stop;
					$dots = '';
					
					if (strlen($blog->getText()) < $max_stop) { 
						$stop = strlen($blog->getText());
						
					} else {
						$dots = '<span class="dots">...</span></div>';
					}
					
					// When using formatted text via CK Editor..
					$start = strpos($blog->getText(), '<p>');
					$stop = strpos($blog->getText(), '</p>');
					
					if ($stop > $max_stop) { 
						$stop = $max_stop;
						
					}
					
					$output .= '<div class="teaser">' . substr($blog->getText(), $start, $stop - $start) . $dots;
				}
				
						
				$output .= '<div class="link"><a href="/blog/' . $blog->getId() . '">Read the blog</a></div>';
				
				$output .= '</div>';
				
				break;
			}
	
		}
		
		return $output;
	}
	
	
	/**
	 * {@inheritdoc}
	 */
	public function blockForm($form, FormStateInterface $form_state) {
		$config = $this->getConfiguration();
		
		$form = parent::blockForm($form, $form_state);
		
		$blog_options = array();
		$blog_options['none'] = '';
		$listOfBlogs = new SortedListOfBlogs();
		$list = $listOfBlogs->getListLatestFirst();
		foreach ($list as $blog) {
			$blog_options[$blog->getCategory()] = $blog->getCategory();
		}
		
		$blog_category = '';
		if (isset($config['cp_blog_blog_category'])) {
			$blog_category = $config['cp_blog_blog_category'];
		}
		
		$description = '';
		if (empty($blog_options)) {
			$description = 'You have none blog. You have to create a CP Blog first.';	
		
		}
		
		$form['cp_blog_blog_category'] = array (
				'#type' => 'select',
				'#title' => $this->t('Select a blog'),
				'#description' => $description,
				'#options' => $blog_options,
				'#default_value' => $blog_category
		);
		
		$date_format = '';
		if (isset($config['cp_blog_date_format'])) {
			$date_format = $config['cp_blog_date_format'];
		}
		
		$date_format_options = array('year-month-day' => 'year-month-day', 'day-month-year' => 'day-month-year');
		
		$form['cp_blog_date_format'] = array (
				'#type' => 'select',
				'#title' => $this->t('Select a date format'),
				'#description' => '',
				'#options' => $date_format_options,
				'#default_value' => $date_format
		);
		
		return $form;
	}
	
	
	/**
	 * {@inheritdoc}
	 */
	public function blockSubmit($form, FormStateInterface $form_state) {
		$this->setConfigurationValue('cp_blog_blog_category', $form_state->getValue('cp_blog_blog_category'));
		$this->setConfigurationValue('cp_blog_date_format', $form_state->getValue('cp_blog_date_format'));
	}
}