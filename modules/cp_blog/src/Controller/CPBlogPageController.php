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
	        '#markup' => $this->_build_html($blog, $list),
	    	'#attached' => array(
	    		'library' =>  array(
	    			'cp_blogs/style',
	    			'cp_blogs/script'
	    		),
	    	),
	    );
	}
  
	function _build_html($blog, $list) {
		$url = $GLOBALS['base_url'] . '/' . PublicStream::basePath() . '/';
		
		$output = '<div class="cp_blog">';

		$output .= '<div class="date">' . date('Y-m-d', $blog->getChanged()) . '</div>';

		$output .= '<div class="heading">' . $blog->getTitle() . '</div>';

		if ($blog->getPictureUri() != null && $blog->getPictureUri() != '') {		
			$picture_url = $url . str_replace('public://', '', $blog->getPictureUri());

			$picture_title = '';
			if ($blog->getPictureTitle() != null && $blog->getPictureTitle() != '') { $picture_title = $blog->getPictureTitle(); }
				
			$output .= '<div class="picture">';
			$output .= '<img src="' . $picture_url . '" alt="' . $picture_title . '" title="' . $picture_title . '" />';
			$output .= '</div>';
		}

		$output .= '<div class="text">' . $blog->getBody() . '</div>';

		$output .= '</div>';
		
		
		foreach ($list as $b) {
			if ($blog->getId() != $b->getId() && $blog->getCategory() == $b->getCategory()) {
				$output .= '<div class="cp_blog_earlier">';
				
				$output .= '<div class="panel-group">';
				$output .= '<div class="panel panel-default">';
				
				$output .= '<div class="panel-heading">';
				$output .= '<h4 class="panel-title">';
				$output .= '<a data-toggle="collapse" href="#cp_blog_earlier_panel_'. $b->getId() .'">';
				$output .= '<span class="panel_heading_date">' . date('Y-m-d', $b->getChanged()) . '</span><span class="panel_heading_title">' . $b->getTitle() . '</span>'; 
				$output .= '</a>';
				$output .= '</h4>';
				$output .= '</div>';
				
				$output .= '<div id="cp_blog_earlier_panel_'. $b->getId() .'" class="panel-collapse collapse">';
				$output .= '<div class="panel-body">';
				
				$output .= '<div class="date">' . date('Y-m-d', $b->getChanged()) . '</div>';
				
				$output .= '<div class="heading">' . $b->getTitle() . '</div>';
				
				if ($b->getPictureUri() != null && $b->getPictureUri() != '') {
					$picture_url_earlier = $url . str_replace('public://', '', $b->getPictureUri());
				
					$picture_title_earlier = '';
					if ($b->getPictureTitle() != null && $b->getPictureTitle() != '') { $picture_title = $b->getPictureTitle(); }
				
					$output .= '<div class="picture">';
					$output .= '<img src="' . $picture_url_earlier . '" alt="' . $picture_title_earlier . '" title="' . $picture_title_earlier . '" />';
					$output .= '</div>';
				}
				
				$output .= '<div class="text">' . $b->getBody() . '</div>';
				
				$output .= '</div>';
				$output .= '</div>';
				
				$output .= '</div>';
				$output .= '</div>';
				
				$output .= '</div>';
				
			}
		}
		
		return $output;
	}
}