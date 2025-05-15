<?php
namespace Drupal\cp_search\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the cp_search module.
 */
class SearchController extends ControllerBase {
    public function showresults() {
        return [
          '#body' => $this->t('Hello from your custom controller!'),
          '#theme' => 'search_results_page',
        ];
      }
}