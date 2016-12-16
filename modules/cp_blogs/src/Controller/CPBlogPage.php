<?php

namespace Drupal\cp_blogs\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\cp_blogs\CPBlogs\SortedListOfBlogs;
use Drupal\Core\StreamWrapper\PublicStream;

class CPBlogPage extends ControllerBase {
	
	public function content($blog_id) {
	  	
	  	$listOfBlogs = new SortedListOfBlogs();
		$list = $listOfBlogs->getListLatestFirst();
	  	
		$settings = \Drupal::service('config.factory')->getEditable('cp_blogs.settings');
		$date_format = 'Y-m-d';
		
		if ($settings->get('settings.date_format') == 'day-month-year') { $date_format = 'd-m-Y'; }
		
		$list_of_elements = array();
		$list_of_elements['site_home'] = "https://$_SERVER[HTTP_HOST]";
		
		$recent_span = time() - (90 * 24 * 60 * 60);
		
		$blog_category = '';
	  	$has_blog = 0;
	  	
	  	foreach ($list as $b) {
	  		if ($b->getId() == $blog_id) {
	  			$b->setChanged(date($date_format, $b->getChanged()));
	  			$this->_formatPictureUri($b);
	  			$list_of_elements['blog'] = $b;
	  			$list_of_elements['page_title'] = $b->getCategory();
	  			$list_of_elements['blog_category'] = $b->getCategory();
	  			
	  			$has_blog = 1;
	  			break;
	  		}	
	  	}
	  	
	  	if ($has_blog) {
	  		
	  		foreach ($list as $b) {
	  			if (($b->getCategory() == $list_of_elements['blog_category'])
	  					&& ($b->getId() != $blog_id)
	  					&& ($b->getChanged() > $recent_span)) {
	  		
	  						$b->setChanged(date($date_format, $b->getChanged()));
	  						$this->_formatPictureUri($b);
	  						$list_of_elements['recent_blogs'][] = $b;
	  			}
	  		}
	  		
		    return array(
	        	'#theme' => 'page_with_cp_blog',
				'#elements' => $list_of_elements,
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
 
	function _formatPictureUri($blog) {
		if ($blog->getPictureUri() != null && $blog->getPictureUri() != '') {
			$blog->setPictureUri('/' . PublicStream::basePath() . '/' . str_replace('public://', '', $blog->getPictureUri()));
		
			if ($blog->getPictureTitle() != null && $blog->getPictureTitle() != '') { 
				$blog->setPictureTitle($blog->getPictureTitle()); 
			}
			
		} else {
			$blog->setPictureUri('');
			$blog->setPictureTitle('');
		}		
	}
}