<?php

namespace Drupal\cp_services\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'described_data_widget' widget.
 *
 * @FieldWidget(
 *   id = "described_data_widget",
 *   module = "cp_services",
 *   label = "Described data",
 *   field_types = {
 *     "described_data"
 *   }
 * )
 */
class DescribedDataWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $descriptor = $items[$delta]?->descriptor ?? '';
    $descriptor_label = $this->getFieldSetting('descriptor_label') ?? $this->t('Descriptor');
    $descriptor_element = [
      '#type' => 'textfield',
      '#default_value' => $descriptor,
      '#title' => $descriptor_label,
      '#size' => 64,
      '#maxlength' => 64,
    ];
    $data = $items[$delta]?->data ?? '';
    $data_label = $this->getFieldSetting('data_label') ?? $this->t('Data');
    $data_element = [
      '#type' => 'textfield',
      '#default_value' => $data,
      '#title' => $data_label,
      '#size' => 64,
      '#maxlength' => 64,
    ];
    return ['descriptor' => $descriptor_element, 'data' => $data_element];
  }
}
