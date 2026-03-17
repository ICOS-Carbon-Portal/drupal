<?php

namespace Drupal\cp_layouts\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;

/**
 * Layout class for the Light Blue three-column with title layout.
 */
class LightBlueThreeColumnTitleLayout extends LayoutDefault {

  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'spacing_top' => 5,
      'spacing_bottom' => 5,
    ];
  }

  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $options = array_combine(range(0, 6), range(0, 6));
    $form['spacing_top'] = [
      '#type' => 'select',
      '#title' => $this->t('Top spacing'),
      '#options' => $options,
      '#default_value' => $this->configuration['spacing_top'],
    ];
    $form['spacing_bottom'] = [
      '#type' => 'select',
      '#title' => $this->t('Bottom spacing'),
      '#options' => $options,
      '#default_value' => $this->configuration['spacing_bottom'],
    ];
    return $form;
  }

  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration['spacing_top'] = $form_state->getValue('spacing_top');
    $this->configuration['spacing_bottom'] = $form_state->getValue('spacing_bottom');
  }

}
