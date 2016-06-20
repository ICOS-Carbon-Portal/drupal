<?php

namespace Drupal\cp_images\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cp_images\CPImages\SortedListOfImages;
use Drupal\Core\StreamWrapper\PublicStream;

/**
 * @Block(
 *   id = "cp_images_carousel",
 *   admin_label = @Translation("CP Images Carousel"),
 * )
 */
class CpImagesCarousel extends BlockBase {
	
	function build() {
		$listOfImages = new SortedListOfImages();
		$list = $listOfImages->getListLatestFirst();
		
		if ($list != null) {
		
			return array(
				'#markup' => $this->_build_html($list),
				'#attached' => array(
					'library' =>  array(
						'cp_images/style',
						'cp_images/script'
					),
				),
			);
		
		} else {
			return array('#markup' => '',);
		}
	}
	
	function _build_html($images) {
	
		$max_width = 0;
		$max_height = 0;
		foreach ($images as $image) {
			if ($image->getPictureWidth() > $max_width) {$max_width = $image->getPictureWidth(); }
			if ($image->getPictureHeight() > $max_height) {$max_height = $image->getPictureHeight(); }
		}
		
		
		$output .= '<div id="cp_images_carousel" class="carousel slide" data-ride="carousel" data-interval="5000">';
		
		
// 		$output .= '<div class="control_left">';
// 		$output .= '<a class="left carousel-control" href="#cp_images_carousel" role="button" data-slide="prev" title="Previous">';
// 		$output .= '<span class="" aria-hidden="true">Prev</span>';
//  	$output .= '<span class="sr-only">Previous</span>';
// 		$output .= '</a>';
// 		$output .= '</div>';

// 		$output .= '<div class="control_right">';
// 		$output .= '<a class="right carousel-control" href="#cp_images_carousel" role="button" data-slide="next" title="Next">';
// 		$output .= '<span class="" aria-hidden="true">Next</span>';
// 		$output .= '<span class="sr-only">Next</span>';
// 		$output .= '</a>';
// 		$output .= '</div>';
		
		
		$output .= '<div class="carousel-inner vertical" role="listbox">';
		
		
		$url = '/' . PublicStream::basePath() . '/';

		$co = 0;
		foreach ($images as $image) {
			
			if ($image->getHistorical() == '0' && $image->getCarousel() == '1') {
					
				$picture_uri = $url . str_replace('public://', '', $image->getPictureUri());
				$picture_title = '';
				if ($image->getPictureTitle() != null && $image->getPictureTitle() != '') { $picture_title = $image->getPictureTitle(); }
				
				
				
				if ($image->getPictureWidth() < $max_width || $image->getPictureHeight() < $max_height) {
					$new_file = 'sites/default/files/new_'. $image->getPictureName();
					
					$im = imagecreatetruecolor($max_width, $max_height);
					$bgc = imagecolorallocate($im, 255, 255, 255);
					imagefilledrectangle($im, 0, 0, $max_width, $max_height, $bgc);
					imagejpeg($im, $new_file);
					
					$dst_im = imagecreatefromjpeg($new_file);
					$src_im = imagecreatefromjpeg('sites/default/files/' . str_replace('public://', '', $image->getPictureUri()));
					$dst_x = ($max_width - $image->getPictureWidth()) / 2;
					$dst_y = 0; //($max_height - $image->getPictureHeight()) / 2;
					$src_x = 0;
					$src_y = 0;
					$src_w = $image->getPictureWidth();;
					$src_h = $image->getPictureHeight();
					$pct = 100;
					
					imagecopymerge($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct);				
					imagejpeg($dst_im, $new_file);	
					$picture_uri = $url.'new_'. $image->getPictureName();
					
				}
				
				if ($co === 0) {
					$output .= '<div class="item active">';
					$co ++;
				
				} else {
					$output .= '<div class="item">';
				}
				
				
				$output .= '<img src="' . $picture_uri . '" title="' . $picture_title . '" />';
				
				$output .= '</div>';	
		
			}
		}
		
		$output .= '</div>';
		$output .= '</div>';
		
	
		return $output;
	}
}