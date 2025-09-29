<?php

namespace Drupal\cp_services\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'contact_described_data_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "contact_described_data_formatter",
 *   label = "Contact Info",
 *   field_types = {
 *     "described_data"
 *   }
 * )
 */
class ContactDescribedDataFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Uses descriptor as link text and data as an email address');
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
        '#attributes' => ['class' => ['email-described-data-item']],
        'email' => [
          '#type' => 'html_tag',
          '#tag' => 'a',
          '#attributes' => ['href' => "mailto:" . $item->data],
          '#value' => $item->descriptor,
        ],
      ];
    }

    return $element;
  }

}
