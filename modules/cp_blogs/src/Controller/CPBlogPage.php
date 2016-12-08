<?php

namespace Drupal\cp_blogs\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\cp_blogs\CPBlogs\SortedListOfBlogs;
use Drupal\Core\StreamWrapper\PublicStream;

class CPBlogPage extends ControllerBase {
	
	public function content($blog_id) {
	  	
	  	$listOfBlogs = new SortedListOfBlogs();
		$list = $listOfBlogs->getListLatestFirst();
	  	
	  	$blog = null;
	  	foreach ($list as $b) {
	  		if ($b->getId() == $blog_id) {
	  			$blog = $b;
	  			break;
	  		}
	  	}
	  	
	  	if ($blog) {
		    return array(
		        '#markup' => $this->_build_html($blog),
		    	'#attached' => array(
		    		'library' =>  array(
		    			'cp_blogs/style',
		    			'cp_blogs/script'
		    		),
		    	),
		    );
		    
	  	} else {
	  		return array('#markup' => '');
	  	} 	
	}
  
	function _build_html($blog) {
		
		$settings = \Drupal::service('config.factory')->getEditable('cp_blog.settings');
		$date_format = 'Y-m-d';
		if ($settings->get('settings.date_format') == 'day-month-year') { $date_format = 'd-m-Y'; }
		
		$url = $GLOBALS['base_url'] . '/' . PublicStream::basePath() . '/';
		
		$output = '<h1 class="page-title">' . $blog->getCategory() . '</h1>';
		
		$output .= '<div class="cp_blog">';

		$output .= '<div class="date">' . date($date_format, $blog->getChanged()) . '</div>';

		$output .= '<div class="heading">' . $blog->getTitle() . '</div>';

		if ($blog->getPictureUri() != null && $blog->getPictureUri() != '') {		
			$picture_url = $url . str_replace('public://', '', $blog->getPictureUri());

			$picture_title = '';
			if ($blog->getPictureTitle() != null && $blog->getPictureTitle() != '') { $picture_title = $blog->getPictureTitle(); }
				
			$output .= '<div class="text"><img src="' . $picture_url . '" alt="' . $picture_title . '" title="' . $picture_title . '" />' . $blog->getBody() . '</div>';
			
		} else {
	  		$output .= '<div class="text">' . $blog->getBody() . '</div>';	
	  	}

		$output .= '</div>';
		
		return $output;
	}
}