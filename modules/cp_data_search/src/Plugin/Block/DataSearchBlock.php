<?php

namespace Drupal\cp_data_search\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Data search' Block.
 *
 * @Block(
 *   id = "data_search_block",
 *   admin_label = @Translation("Data search block"),
 *   category = @Translation("Data search"),
 * )
 */
class DataSearchBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#theme' => 'data_search_block',
      '#attached' => array(
        'library' => array(
          'cp_data_search/style',
          'cp_data_search/script',
        ),
      ),
    );
  }

}
