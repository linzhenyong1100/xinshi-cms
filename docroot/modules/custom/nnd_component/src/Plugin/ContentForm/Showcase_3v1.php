<?php

namespace Drupal\nnd_component\Plugin\ContentForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\nnd_component\Plugin\ContentFormBase;

/**
 * Class Showcase_4v1
 *
 * @ContentWidgetAnnotation(
 *   id = "content_form_showcase_4v1",
 *   label = @Translation("Showcase 4v1"),
 *   admin_label = @Translation("Showcase 4v1"),
 *   description = @Translation("Showcase 4v1"),
 *   entity = "block_content:showcase_4v1",
 *   plugin_path = {
 *     "type" = "module",
 *     "name" = "content_form",
 *     "directory" = "src/Plugin/ContentForm/Showcase_4v1",
 *   },
 * )
 */
class Showcase_4v1 extends ContentFormBase {

  /**
   * {@inheritdoc}
   */
  public function formAlter(&$form, FormStateInterface $form_state) {
    // TODO: Implement alter() method.
    $form['icon']['#states'] = [
      'visible' => ['select[name="title_style"]' => ['value' => 'style-v2']],
    ];
    $form['icon']['widget'][0]['value']['#states'] = [
      'required' => ['select[name="title_style"]' => ['value' => 'style-v2']],
    ];
  }
}