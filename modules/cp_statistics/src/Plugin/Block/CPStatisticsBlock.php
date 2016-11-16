<?php

namespace Drupal\cp_statistics\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *   id = "cp_statistics",
 *   admin_label = @Translation("CP statistics"),
 * )
 */
class CpStatisticsBlock extends BlockBase {
	
	function build() {
		$config = $this->getConfiguration();
		
		$list_of_elements['CP_STATISTICS']['TITLE'] = 'STATISTICS';
		
		return array(
			'#theme' => 'cp_statistics',
			'#elements' => $list_of_elements,
			'#attached' => array(
				'library' =>  array(
					'cp_statistics/style',
					'cp_statistics/script'
				),
			),
		);
		
	}
	
	public function blockForm($form, FormStateInterface $form_state) {
		
	}
	
	public function blockSubmit($form, FormStateInterface $form_state) {
		
	}
}