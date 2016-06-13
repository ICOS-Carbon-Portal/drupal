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
			);
		
		} else {
			return array('#markup' => '',);
		}
	}
	
	function _build_html($list) {
		$config = $this->getConfiguration();
		
		$blog_title = '';
		if (isset($config['cp_blog_blog_title'])) {
			$blog_title = $config['cp_blog_blog_title'];
		}
		
		$output = '';
		
		foreach ($list as $blog) {
			
			if ($blog->getTitle() == $blog_title) {
				
				$output .= '<div class="cp_blog_teased">';
				
				$output .= '<div class="date">' . date('Y-m-d', $blog->getChanged()) . '</div>';
				
				$output .= '<div class="heading">' . $blog->getTitle() . '</div>';
						
				if ($blog->getPictureUrl() != null || $blog->getPictureUrl() != '') {
					$url = '/' . PublicStream::basePath() . '/';
					$picture_url = $url . str_replace('public://', '', $blog->getPictureUrl());
						
					$picture_title = '';
					if ($blog->getPictureTitle() != null || $blog->getPictureTitle() != '') { $picture_title = $blog->getPictureTitle(); }
					
					$output .= '<div class="picture">';
					$output .= '<img src="' . $picture_url . '" alt="' . $picture_title . '" title="' . $picture_title . '" />';
					$output .= '</div>';
				}
				
				
				
				$text = '';
				if (strlen($blog->getText()) > 1 ) {
					$text_start = strpos($blog->getText(), '<p>');
					$text_stop = strpos($blog->getText(), '</p>');
					
					$output .= '<div class="teaser">' . substr($blog->getText(), $text_start, $text_stop - $text_start) . '<span class="dots">...</span></div>';
				}
				
				
						
				$output .= '<div class="link"><a href="/blog/' . $blog->getId() . '">Read the blog</a></div>';
				
				$output .= '</div>';
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
			$blog_options[$blog->getTitle()] = $blog->getTitle();
		}
		
		$blog_title = '';
		if (isset($config['cp_blog_blog_title'])) {
			$blog_title = $config['cp_blog_blog_title'];
		}
		
		$description = '';
		if (empty($blog_options)) {
			$description = 'You have none blog. You have to create a CP Blog first.';	
		
		}
		
		$form['cp_blog_blog_title'] = array (
				'#type' => 'select',
				'#title' => $this->t('Select a blog'),
				'#description' => $description,
				'#options' => $blog_options,
				'#default_value' => $blog_title
		);
		
		return $form;
	}
	
	
	/**
	 * {@inheritdoc}
	 */
	public function blockSubmit($form, FormStateInterface $form_state) {
		$this->setConfigurationValue('cp_blog_blog_title', $form_state->getValue('cp_blog_blog_title'));
	}
}