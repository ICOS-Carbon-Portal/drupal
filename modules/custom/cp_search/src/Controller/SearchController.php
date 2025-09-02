<?php

namespace Drupal\cp_search\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for search_results_page from the cp_search module.
 */
class SearchController extends ControllerBase {
    public function content() {
        return [
          '#theme' => 'search_results_page'
        ];
      }
}
