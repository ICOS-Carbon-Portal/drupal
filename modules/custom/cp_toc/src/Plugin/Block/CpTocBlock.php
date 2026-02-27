<?php

declare(strict_types=1);

namespace Drupal\cp_toc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;
use Drupal\node\NodeInterface;

/**
 * Provides the CP Table of Contents block.
 *
 * Settings are resolved in this priority order:
 *  1. Block's own configuration when "Override content-type settings" is on.
 *  2. The current node-type's third-party settings (cp_toc).
 *
 * Per-node base fields (cp_toc_enabled, cp_toc_selectors_override,
 * cp_toc_selectors) are always applied on top of whichever source wins.
 *
 * @Block(
 *   id = "cp_toc_block",
 *   admin_label = @Translation("CP Table of Contents"),
 *   category = @Translation("CP")
 * )
 */
class CpTocBlock extends BlockBase {

  // ---------------------------------------------------------------------------
  // Configuration
  // ---------------------------------------------------------------------------

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    return [
      'override_nodetype' => FALSE,
    ] + $this->defaultTocSettings();
  }

  /**
   * Default TOC display settings.  Mirrors cp_toc_default_settings().
   */
  protected function defaultTocSettings(): array {
    return [
      'headings'          => 'h2, h3',
      'title'             => 'Contents',
      'title_classes'     => '',
      'list_classes'      => '',
      'list_item_classes' => '',
      'link_classes'      => '',
      'min_headings'      => 2,
      'smooth_scroll'     => TRUE,
      'scroll_offset'     => 0,
      'highlight'         => TRUE,
      'back_to_top'       => FALSE,
      'back_to_top_label' => 'Back to top',
    ];
  }

  // ---------------------------------------------------------------------------
  // Block form
  // ---------------------------------------------------------------------------

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state): array {
    $form   = parent::blockForm($form, $form_state);
    $config = $this->configuration;

    $form['override_nodetype'] = [
      '#type'          => 'checkbox',
      '#title'         => $this->t('Override content-type settings'),
      '#description'   => $this->t('Use the settings below instead of those configured on the content type.'),
      '#default_value' => $config['override_nodetype'],
    ];

    $form['toc_settings'] = [
      '#type'   => 'fieldset',
      '#title'  => $this->t('TOC options'),
      '#states' => [
        'visible' => [':input[name="settings[override_nodetype]"]' => ['checked' => TRUE]],
      ],
    ];

    $form['toc_settings']['headings'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Heading selectors'),
      '#default_value' => $config['headings'],
      '#placeholder'   => 'h2, h3',
      '#description'   => $this->t('Comma-separated CSS selectors, e.g. <code>h2, h3</code>.'),
    ];

    $form['toc_settings']['title'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('TOC title'),
      '#default_value' => $config['title'],
      '#description'   => $this->t('Label shown above the list. Leave empty to hide.'),
    ];

    $form['toc_settings']['title_classes'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Title CSS classes'),
      '#default_value' => $config['title_classes'],
      '#description'   => $this->t('Space-separated CSS classes added to the title element.'),
    ];

    $form['toc_settings']['list_classes'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('List container CSS classes'),
      '#default_value' => $config['list_classes'],
      '#description'   => $this->t('Space-separated CSS classes added to the <code>&lt;ul&gt;</code> element.'),
    ];

    $form['toc_settings']['list_item_classes'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('List item CSS classes'),
      '#default_value' => $config['list_item_classes'],
      '#description'   => $this->t('Space-separated CSS classes added to every <code>&lt;li&gt;</code> item.'),
    ];

    $form['toc_settings']['link_classes'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('TOC link CSS classes'),
      '#default_value' => $config['link_classes'],
      '#description'   => $this->t('Space-separated CSS classes added to every <code>&lt;a&gt;</code> link inside the TOC.'),
    ];

    $form['toc_settings']['min_headings'] = [
      '#type'          => 'number',
      '#title'         => $this->t('Minimum headings'),
      '#default_value' => $config['min_headings'],
      '#min'           => 1,
      '#max'           => 20,
    ];

    $form['toc_settings']['smooth_scroll'] = [
      '#type'          => 'checkbox',
      '#title'         => $this->t('Smooth scrolling'),
      '#default_value' => $config['smooth_scroll'],
    ];

    $form['toc_settings']['scroll_offset'] = [
      '#type'          => 'number',
      '#title'         => $this->t('Scroll offset (px)'),
      '#default_value' => $config['scroll_offset'],
      '#description'   => $this->t('Pixel offset when jumping to a heading (e.g. for a fixed header).'),
    ];

    $form['toc_settings']['highlight'] = [
      '#type'          => 'checkbox',
      '#title'         => $this->t('Highlight active item on scroll'),
      '#default_value' => $config['highlight'],
    ];

    $form['toc_settings']['back_to_top'] = [
      '#type'          => 'checkbox',
      '#title'         => $this->t('Show "back to top" links next to headings'),
      '#default_value' => $config['back_to_top'],
    ];

    $form['toc_settings']['back_to_top_label'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('"Back to top" label'),
      '#default_value' => $config['back_to_top_label'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state): void {
    $this->configuration['override_nodetype'] = (bool) $form_state->getValue('override_nodetype');

    $sub = $form_state->getValue('toc_settings');

    $this->configuration['headings']          = trim($sub['headings'] ?? '') ?: 'h2, h3';
    $this->configuration['title']             = $sub['title'] ?? 'Contents';
    $this->configuration['title_classes']     = $sub['title_classes'] ?? '';
    $this->configuration['list_classes']      = $sub['list_classes'] ?? '';
    $this->configuration['list_item_classes'] = $sub['list_item_classes'] ?? '';
    $this->configuration['link_classes']      = $sub['link_classes'] ?? '';
    $this->configuration['min_headings']      = (int) ($sub['min_headings'] ?? 2);
    $this->configuration['smooth_scroll']     = (bool) ($sub['smooth_scroll'] ?? TRUE);
    $this->configuration['scroll_offset']     = (int) ($sub['scroll_offset'] ?? 0);
    $this->configuration['highlight']         = (bool) ($sub['highlight'] ?? TRUE);
    $this->configuration['back_to_top']       = (bool) ($sub['back_to_top'] ?? FALSE);
    $this->configuration['back_to_top_label'] = $sub['back_to_top_label'] ?? 'Back to top';
  }

  // ---------------------------------------------------------------------------
  // Render
  // ---------------------------------------------------------------------------

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $settings = $this->resolveSettings();

    if (empty($settings)) {
      return [];
    }

    $cache_tags = $this->getCacheTags();
    $node       = $this->getCurrentNode();
    if ($node) {
      $cache_tags = array_merge($cache_tags, $node->getCacheTags());
    }

    // Render the inner nav + list via the cp_toc theme hook. The outer block
    // wrapper (with .cp-toc class, data-* attrs, and `hidden`) is provided by
    // block--cp-toc-block.html.twig via cp_toc_preprocess_block().
    return [
      '#theme'        => 'cp_toc',
      '#title'        => $settings['title'],
      '#title_classes' => $settings['title_classes'],
      '#attached'     => ['library' => ['cp_toc/toc']],
      '#cache'        => [
        'contexts' => ['url.path'],
        'tags'     => $cache_tags,
      ],
    ];
  }

  // ---------------------------------------------------------------------------
  // Helpers
  // ---------------------------------------------------------------------------

  /**
   * Returns the data needed by cp_toc_preprocess_block(), or NULL.
   *
   * Called from the preprocess hook so attributes can be set on the block
   * wrapper element without relying on render-array properties surviving the
   * render pipeline.
   *
   * @return array|null
   *   Keys: title, title_classes, data_attributes (map of attr → value).
   *   NULL when the block should not render (settings are empty).
   */
  public function getPreprocessData(): ?array {
    $settings = $this->resolveSettings();
    if (empty($settings)) {
      return NULL;
    }

    return [
      'title'           => $settings['title'],
      'title_classes'   => $settings['title_classes'],
      'data_attributes' => [
        'data-headings'          => $settings['headings'],
        'data-min-headings'      => (string) $settings['min_headings'],
        'data-smooth-scroll'     => $settings['smooth_scroll'] ? '1' : '0',
        'data-scroll-offset'     => (string) $settings['scroll_offset'],
        'data-highlight'         => $settings['highlight'] ? '1' : '0',
        'data-back-to-top'       => $settings['back_to_top'] ? '1' : '0',
        'data-back-to-top-label' => $settings['back_to_top_label'],
        'data-list-classes'      => $settings['list_classes'],
        'data-list-item-classes' => $settings['list_item_classes'],
        'data-link-classes'      => $settings['link_classes'],
      ],
    ];
  }

  /**
   * Returns the node entity for the current request, or NULL.
   */
  protected function getCurrentNode(): ?NodeInterface {
    $node = \Drupal::routeMatch()->getParameter('node');
    return ($node instanceof NodeInterface) ? $node : NULL;
  }

  /**
   * Resolves effective TOC settings.  Returns [] when the block should not render.
   */
  protected function resolveSettings(): array {
    $defaults = $this->defaultTocSettings();

    // Block-level override wins unconditionally.
    if (!empty($this->configuration['override_nodetype'])) {
      $settings = array_intersect_key($this->configuration, $defaults) + $defaults;
      return $this->applyPerNodeOverrides($settings);
    }

    // Fall back to node-type third-party settings.
    $node = $this->getCurrentNode();
    if (!$node) {
      return [];
    }

    $node_type = NodeType::load($node->bundle());
    if (!$node_type) {
      return [];
    }

    $type_settings = $node_type->getThirdPartySettings('cp_toc');
    if (empty($type_settings['enabled'])) {
      return [];
    }

    return $this->applyPerNodeOverrides($type_settings + $defaults);
  }

  /**
   * Applies per-node base-field overrides to a resolved settings array.
   *
   * Returns [] if the node has explicitly disabled the TOC.
   */
  protected function applyPerNodeOverrides(array $settings): array {
    $node = $this->getCurrentNode();
    if (!$node) {
      return $settings;
    }

    try {
      // If the editor unchecked "Enable TOC on this page", suppress the block.
      $node_enabled = $node->get('cp_toc_enabled')->value;
      if ($node_enabled !== NULL && !(bool) $node_enabled) {
        return [];
      }

      // Apply custom selectors when the per-node selector override is active.
      if ((bool) $node->get('cp_toc_selectors_override')->value) {
        $custom = trim($node->get('cp_toc_selectors')->value ?? '');
        if ($custom !== '') {
          $settings['headings'] = $custom;
        }
      }

      // Apply custom minimum-headings threshold when the override is active.
      if ((bool) $node->get('cp_toc_min_headings_override')->value) {
        $settings['min_headings'] = (int) ($node->get('cp_toc_min_headings')->value ?? $settings['min_headings']);
      }
    }
    catch (\Exception $e) {
      // Base fields not installed – ignore per-node overrides.
    }

    return $settings;
  }

}
