<?php

namespace Drupal\cp_fluxes\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'FLUXES full issue link' Block.
 *
 * @Block(
 *   id = "fluxes_full_issue_link",
 *   admin_label = @Translation("FLUXES full issue link"),
 *   category = @Translation("FLUXES"),
 * )
 */
class FluxesfullIssueLinkBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#theme' => 'fluxes_full_issue_link',
      '#icon' => '/' . \Drupal::service('extension.list.module')->getPath('cp_fluxes') . '/images/tick-icon.svg'
    );
  }

}
