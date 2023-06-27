<?php

namespace Drupal\cp_fluxes\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'FLUXES header' Block.
 *
 * @Block(
 *   id = "fluxes_header",
 *   admin_label = @Translation("FLUXES header"),
 *   category = @Translation("FLUXES"),
 * )
 */
class FluxesHeaderBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#theme' => 'fluxes_header',
      '#header_image' => '/' . \Drupal::service('extension.list.module')->getPath('cp_fluxes') . '/images/FLUXES-header.svg'
    );
  }

}
