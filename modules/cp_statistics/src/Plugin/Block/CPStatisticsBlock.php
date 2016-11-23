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

		if ($config['cp_statistics_data_url'] != '') {
			$elements['CP_STATISTICS'] = '';
			
			return array(
				'#theme' => 'cp_statistics',
				'#elements' => $elements,
				'#attached' => array(
					'library' =>  array(
						'cp_statistics/style',
						'cp_statistics/script'
					),
					'drupalSettings' => array(
						'data_url' => $config['cp_statistics_data_url'],
						'type' => 'setting',
					),
				),
			);
			
		} else {
			return array('#markup' => '',);
		}
	}
	
	public function blockForm($form, FormStateInterface $form_state) {
		
		$form = parent::blockForm($form, $form_state);
		$config = $this->getConfiguration();
		
		$data_url = '';
		if (isset($config['cp_statistics_data_url'])) {
			$data_url = $config['cp_statistics_data_url'];
		}
		
		$form['cp_statistics_data_url'] = array (
				'#type' => 'textfield',
				'#title' => $this->t('URL to the Rest service'),
				'#default_value' => $data_url
		);
		return $form;
	}
	
	public function blockSubmit($form, FormStateInterface $form_state) {
		$this->setConfigurationValue('cp_statistics_data_url', $form_state->getValue('cp_statistics_data_url'));
	}
}