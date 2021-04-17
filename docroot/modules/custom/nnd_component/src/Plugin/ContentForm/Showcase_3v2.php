<?php

namespace Drupal\nnd_component\Plugin\ContentForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\nnd_component\Plugin\ContentFormBase;

/**
 * Class Showcase_3v2
 *
 * @ContentWidgetAnnotation(
 *   id = "content_form_showcase_3v2",
 *   label = @Translation("Showcase 3v2"),
 *   admin_label = @Translation("Showcase 3v2"),
 *   description = @Translation("Showcase 3v2"),
 *   entity = "block_content:showcase_3v2",
 *   plugin_path = {
 *     "type" = "module",
 *     "name" = "content_form",
 *     "directory" = "src/Plugin/ContentForm/Showcase_3v2",
 *   },
 * )
 */
class Showcase_3v2 extends ContentFormBase {

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