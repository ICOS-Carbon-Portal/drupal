<?php

namespace Drupal\cp_station_list\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Station list' Block.
 *
 * @Block(
 *   id = "station_list_block",
 *   admin_label = @Translation("Station list"),
 *   category = @Translation("Station list"),
 * )
 */
class StationListBlock extends BlockBase
{

	/**
	 * {@inheritdoc}
	 */
	public function build()
	{
		$country = $this->configuration['station_list_block_country'] ?? '';

		return [
			'#theme' => 'station_list_block',
			'#attached' => [
				'drupalSettings' => [
					'stationList' => [
						'country' => $country
					]
				],
				'library' => [
					'cp_station_list/station-list-block'
				],
			]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function blockForm($form, FormStateInterface $form_state)
	{
		$form['station_list_block_country'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Country'),
			'#default_value' => $this->configuration['station_list_block_country'],
		];

		return $form;
	}

	/**
	 * {@inheritdoc}
	 */
	public function blockSubmit($form, FormStateInterface $form_state)
	{
		$this->setConfigurationValue('station_list_block_country', $form_state->getValue('station_list_block_country'));
	}
}
