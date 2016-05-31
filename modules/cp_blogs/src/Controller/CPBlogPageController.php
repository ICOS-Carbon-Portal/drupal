<?php

namespace Drupal\cp_blogs\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\cp_blogs\CPBlogs\SortedListOfBlogs;
use Drupal\Core\StreamWrapper\PublicStream;

class CPBlogPageController extends ControllerBase {
	
	public function content($blog_id) {
	  	
	  	$listOfBlogs = new SortedListOfBlogs();
		$list = $listOfBlogs->getListLatestFirst();
	  	
	  	$blog = null;
	  	foreach ($list as $b) {
	  		if ($b->getId() == $blog_id) {
	  			$blog = $b;
	  		}
	  	}
	  	
	    return array(
	        '#markup' => $this->_build_html($blog),
	    	'#attached' => array(
	    		'library' =>  array(
	    			'cp_blogs/style',
	    			'cp_blogs/script'
	    		),
	    	),
	    );
	}
  
	function _build_html($blog) {
		
		$output = '<div class="cp_blog">';

		$output .= '<div class="date">' . date('Y-m-d', $blog->getChanged()) . '</div>';

		$output .= '<div class="heading">' . $blog->getTitle() . '</div>';

		if ($blog->getPictureUrl() != null || $blog->getPictureUrl() != '') {
			$url = $GLOBALS['base_url'] . '/' . PublicStream::basePath() . '/';
			$picture_url = $url . str_replace('public://', '', $blog->getPictureUrl());

			$picture_title = '';
			if ($blog->getPictureTitle() != null || $blog->getPictureTitle() != '') { $picture_title = $blog->getPictureTitle(); }
				
			$output .= '<div class="picture">';
			$output .= '<img src="' . $picture_url . '" alt="' . $picture_title . '" title="' . $picture_title . '" />';
			$output .= '</div>';
		}

		$output .= '<div class="text">' . $blog->getText() . '</div>';

		$output .= '</div>';
		
	
		return $output;
	}
}