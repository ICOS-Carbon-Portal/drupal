<?php

namespace Drupal\cp_datastat\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * @Block(
 *   id = "cp_datastat_contributors",
 *   admin_label = @Translation("CP data statistics over contributors"),
 * )
 */
class CPDatastatContributorsBlock extends BlockBase {
	
	function build() {
		
		return array(
			'#markup' => '<div id="cp_datastat"></div>',
			'#attached' => array(
				'library' =>  array(
					'cp_datastat/style',
					'cp_datastat/script_contributers'
				),
			),
		);
		
	}
}