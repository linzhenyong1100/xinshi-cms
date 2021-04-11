<?php

namespace Drupal\nnd_component\Plugin\ContentForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\nnd_component\Plugin\ContentFormBase;

/**
 * Class Showcase_1v2
 *
 * @ContentWidgetAnnotation(
 *   id = "content_form_showcase_1v2",
 *   label = @Translation("Showcase 1v2"),
 *   admin_label = @Translation("Showcase 1v2"),
 *   description = @Translation("Showcase 1v2"),
 *   entity = "block_content:showcase_1v2",
 *   plugin_path = {
 *     "type" = "module",
 *     "name" = "content_form",
 *     "directory" = "src/Plugin/ContentForm/Showcase_1v2",
 *   },
 * )
 */
class Showcase_1v2 extends ContentFormBase {

  /**
   * {@inheritdoc}
   */
  public function formAlter(&$form, FormStateInterface $form_state) {
    // TODO: Implement alter() method.
    $form['icon']['#states'] = [
      'visible' => ['select[name="showcase_1v1_title_style"]' => ['value' => 'style-v2']],
    ];
    $form['icon']['widget'][0]['value']['#states'] = [
      'required' => ['select[name="showcase_1v1_title_style"]' => ['value' => 'style-v2']],
    ];
  }
}