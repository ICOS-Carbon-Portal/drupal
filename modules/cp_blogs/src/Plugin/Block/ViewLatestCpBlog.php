<?php

namespace Drupal\cp_blogs\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_blogs\CPBlogs\SortedListOfBlogs;
use Drupal\Core\StreamWrapper\PublicStream;

/**
 * @Block(
 *   id = "view_latest_cp_blog",
 *   admin_label = @Translation("View latest CP blog"),
 * )
 */
class ViewLatestCpBlog extends BlockBase {
	
	function build() {
		$listOfBlogs = new SortedListOfBlogs();
		$list = $listOfBlogs->getListLatestFirst();
		
		if ($list != null) {
		
			return array(
				'#markup' => $this->_build_html($list[0]),
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
	
	function _build_html($blog) {
	
		$output = '<div class="latest-blog">';
				
		$output .= '<div class="date">' . date('Y-m-d', $blog->getCreated()) . '</div>';
		
		$output .= '<div>';
		
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
		
		$output .= '</div>';
				
		$output .= '<div class="text">' . $blog->getText() . '</div>';
		
		$output .= '</div>';
	
		return $output;
	}
}