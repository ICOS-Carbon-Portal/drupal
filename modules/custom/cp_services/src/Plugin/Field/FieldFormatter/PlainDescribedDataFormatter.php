<?php

namespace Drupal\cp_services\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'plain_described_data_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "plain_described_data_formatter",
 *   label = "Plain data",
 *   field_types = {
 *     "described_data"
 *   }
 * )
 */
class PlainDescribedDataFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Displays described data');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      // Render each element as markup.
      $element[$delta] = [
        '#type' => 'container',
        '#attributes' => ['class' => ['described-data-item']],
        'descriptor' => [
          '#type' => 'html_tag',
          '#tag' => 'div',
          '#attributes' => ['class' => ['descriptor']],
          '#value' => $item->descriptor,
        ],
        'data' => [
          '#type' => 'html_tag',
          '#tag' => 'div',
          '#attributes' => ['class' => ['data']],
          '#value' => $item->data,
        ],
      ];
    }

    return $element;
  }
}
