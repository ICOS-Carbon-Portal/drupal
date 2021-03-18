<?php

namespace Drupal\cp_json_menu\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\Core\Menu;
use Drupal\Core\MenuTreeParameters;

/**
 * Provides a Demo Resource
 *
 * @RestResource(
 *   id = "json_menu_resource",
 *   label = @Translation("Json Menu Resource"),
 *   uri_paths = {
 *     "canonical" = "/api/menu/{menu}"
 *   }
 * )
 */
class JsonMenuResource extends ResourceBase {

  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */
  public function get($menu = null) {

    $menu_name = $menu;
    $menu_parameters = \Drupal::menuTree()->getCurrentRouteMenuTreeParameters($menu_name);
    $menu_parameters->onlyEnabledLinks();
    $tree = \Drupal::menuTree()->load($menu_name, $menu_parameters);
    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    $tree = \Drupal::menuTree()->transform($tree, $manipulators);

    $result = $this->getChildren($tree);

    return new ResourceResponse($result);
  }

  private function getChildren($tree) {
    $result = [];

    foreach ($tree as $element) {
      $link = $element->link;
      $result[] = [
        'title' => $link->getTitle(),
        'url' => $link->getUrlObject()->toString(true)->getGeneratedUrl(),
        'children' => $this->getChildren($element->subtree)
      ];
    }

    return $result;
  }
}
