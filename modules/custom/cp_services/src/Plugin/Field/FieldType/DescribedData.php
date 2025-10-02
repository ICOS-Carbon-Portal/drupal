<?php

namespace Drupal\cp_services\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'described_data' field type.
 * Intended to be used for contact info (name, email) and KPIs (label, number)
 *
 * @FieldType(
 *   id = "described_data",
 *   label = "Described data",
 *   module = "cp_services",
 *   description = "Coupled strings, one is considered data and the other the descriptor for that data",
 *   default_widget = "described_data_widget",
 *   default_formatter = "plain_described_data_formatter"
 * )
*/
class DescribedData extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
    // columns contains the values that the field will store
      'columns' => [
        'descriptor' => [
          'type' => 'text',
          'size' => 'small',
          'not null' => FALSE,
        ],
        'data' => [
          'type' => 'text',
          'size' => 'small',
          'not null' => FALSE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $descriptor = $this->get('descriptor')->getValue();
    $data = $this->get('data')->getValue();
    return $descriptor === NULL || $descriptor === '' || $data === NULL || $data === '';
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['descriptor'] = DataDefinition::create('string')
      ->setLabel('Descriptor');
    $properties['data'] = DataDefinition::create('string')
      ->setLabel('Data');

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return [
      'descriptor_label' => 'Descriptor',
      'data_label' => 'Data',
    ] + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    
    $elements = [];
    // The key of the element should be the setting name
    $elements['descriptor_label'] = [
      '#title' => $this->t('Descriptor label'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('descriptor_label'),
    ];
    $elements['data_label'] = [
      '#title' => $this->t('Data label'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('data_label'),
    ];

    return $elements;
  }
}
