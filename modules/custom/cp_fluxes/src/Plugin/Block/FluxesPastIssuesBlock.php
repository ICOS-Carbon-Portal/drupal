<?php

namespace Drupal\cp_fluxes\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'FLUXES past issues' Block.
 *
 * @Block(
 *   id = "fluxes_past_issues",
 *   admin_label = @Translation("FLUXES past issues"),
 *   category = @Translation("FLUXES"),
 * )
 */
class FluxesPastIssuesBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#theme' => 'fluxes_past_issues',
      '#icon' => '/' . \Drupal::service('extension.list.module')->getPath('cp_fluxes') . '/images/past-icon.svg'
    );
  }

}
