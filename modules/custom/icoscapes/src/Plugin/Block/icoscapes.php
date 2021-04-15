<?php

namespace Drupal\icoscapes\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * @Block(
 *   id = "icoscapes_block",
 *   admin_label = @Translation("ICOScapes map"),
 * )
 */
class Icoscapes extends BlockBase {

  function build() {
    $header_image = file_create_url(drupal_get_path('module', 'icoscapes') . '/images/ICOScapes_header.png');
    $map_background = file_create_url(drupal_get_path('module', 'icoscapes') . '/images/ICOScapes_Photo_Campaign_map.png');

    return array(
      '#theme' => 'icoscapes_block',
      '#attached' => array(
        'library' =>  array(
          'icoscapes/icoscapes'
        ),
      ),
      '#header_image' => $header_image,
      '#map_background' => $map_background
    );
  }

}
