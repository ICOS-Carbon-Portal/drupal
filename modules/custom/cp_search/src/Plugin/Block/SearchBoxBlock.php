<?php

namespace Drupal\cp_search\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'CP search' block.
 *
 * @Block(
 *   id = "cp_search_block",
 *   admin_label = "CP Search",
 *   category = "Search"
 * )
 */
class SearchBoxBlock extends BlockBase {

  public function build() {
    return [
      '#theme' => 'cp_search_form_block'
    ];
  }
}
