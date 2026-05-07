<?php

namespace Drupal\cp_layouts\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;

/**
 * Layout class providing configurable video source URLs at three resolutions.
 */
class VideoLayout extends LayoutDefault {

  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'video_url_1080p' => '',
      'video_url_720p' => '',
      'video_url_480p' => '',
    ];
  }

  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $form['video_url_1080p'] = [
      '#type' => 'url',
      '#title' => $this->t('Video URL (1080p)'),
      '#default_value' => $this->configuration['video_url_1080p'],
    ];
    $form['video_url_720p'] = [
      '#type' => 'url',
      '#title' => $this->t('Video URL (720p)'),
      '#default_value' => $this->configuration['video_url_720p'],
    ];
    $form['video_url_480p'] = [
      '#type' => 'url',
      '#title' => $this->t('Video URL (480p)'),
      '#default_value' => $this->configuration['video_url_480p'],
    ];
    return $form;
  }

  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration['video_url_1080p'] = $form_state->getValue('video_url_1080p');
    $this->configuration['video_url_720p'] = $form_state->getValue('video_url_720p');
    $this->configuration['video_url_480p'] = $form_state->getValue('video_url_480p');
  }

}
