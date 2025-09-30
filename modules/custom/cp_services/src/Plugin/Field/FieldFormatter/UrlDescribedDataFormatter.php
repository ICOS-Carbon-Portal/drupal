<?php

namespace Drupal\cp_services\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'url_described_data_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "url_described_data_formatter",
 *   label = "URL format (data is URL or email)",
 *   field_types = {
 *     "described_data"
 *   }
 * )
 */
class UrlDescribedDataFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Uses descriptor as link text and data as a URL or email address');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      // Render each element as markup.
      if (strlen($item->data) > 0) {
        $href = $item->data;
        if (str_contains($href, "@") && !str_contains($href, "mailto:")) {
          $href = "mailto:" . $href;
        }
        $element[$delta] = [
          '#type' => 'html_tag',
          '#tag' => 'a',
          '#attributes' => ['href' => $href],
          '#value' => $item->descriptor,
        ];
      } else {
        $element[$delta] = [
          '#type' => 'html_tag',
          '#tag' => 'span',
          '#value' => $item->descriptor,
        ];
      }
    }

    return $element;
  }

}
