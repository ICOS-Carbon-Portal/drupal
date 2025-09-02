<?php

namespace Drupal\cp_search\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure settings for the cp_search module.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cp_search_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'cp_search.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('cp_search.settings');

    $form['website'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Website short code (e.g. cp or sites)'),
      '#default_value' => $config->get('website'),
    ];

    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Search-only API key for Typesense collection'),
      '#default_value' => $config->get('api_key'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->config('cp_search.settings')
      // Set the submitted configuration setting.
      ->set('website', $form_state->getValue('website'))
      ->set('api_key', $form_state->getValue('api_key'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
