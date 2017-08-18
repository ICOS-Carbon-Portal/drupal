<?php

namespace Drupal\cp_datastat\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * @Block(
 *   id = "cp_datastat_downloads",
 *   admin_label = @Translation("CP data statistics over downloads"),
 * )
 */
class CPDatastatDownloadsBlock extends BlockBase {
	
	function build() {
		
		return array(
				'#markup' => '<div id="cp_datastat"></div>',
				'#attached' => array(
						'library' =>  array(
								'cp_datastat/style',
								'cp_datastat/script_downloads'
						),	
				),	
		);
		
	}
}