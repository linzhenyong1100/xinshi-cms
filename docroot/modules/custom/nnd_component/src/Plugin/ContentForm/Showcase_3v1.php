<?php

namespace Drupal\nnd_component\Plugin\ContentForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\nnd_component\Plugin\BlockContentFormBase;

/**
 * Class Showcase_3v1
 *
 * @ContentWidgetAnnotation(
 *   id = "content_form_showcase_3v1",
 *   label = @Translation("Showcase 3v1"),
 *   admin_label = @Translation("Showcase 3v1"),
 *   description = @Translation("Showcase 3v1"),
 *   entity = "block_content:showcase_3v1",
 *   plugin_path = {
 *     "type" = "module",
 *     "name" = "content_form",
 *     "directory" = "src/Plugin/ContentForm/Showcase_3v1",
 *   },
 * )
 */
class Showcase_3v1 extends BlockContentFormBase {

  /**
   * {@inheritdoc}
   */
  public function formAlter(&$form, FormStateInterface $form_state) {
    // TODO: Implement alter() method.
    parent::formAlter($form, $form_state);
  }
}