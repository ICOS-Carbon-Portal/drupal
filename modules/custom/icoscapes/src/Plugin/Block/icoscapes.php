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
    $icoscapes_module_path = \Drupal::service('extension.list.module')->getPath('icoscapes');
    $header_image = \Drupal::service('file_url_generator')->generateAbsoluteString($icoscapes_module_path . '/images/ICOScapes_header.png');
    $map_background = \Drupal::service('file_url_generator')->generateAbsoluteString($icoscapes_module_path . '/images/ICOScapes_Photo_Campaign_map.png');

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
