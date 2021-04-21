<?php

namespace Drupal\nnd_component\Plugin\ContentForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\nnd_component\Plugin\NodeFormBase;

/**
 * Class Blog
 *
 * @ContentWidgetAnnotation(
 *   id = "content_form_blog",
 *   label = @Translation("Blog"),
 *   admin_label = @Translation("Blog"),
 *   description = @Translation("Blog"),
 *   entity = "node:blog",
 *   plugin_path = {
 *     "type" = "module",
 *     "name" = "content_form",
 *     "directory" = "src/Plugin/ContentForm/Blog",
 *   },
 * )
 */
class Blog extends NodeFormBase {

  /**
   * {@inheritdoc}
   */
  public function formAlter(&$form, FormStateInterface $form_state) {
    // TODO: Implement alter() method.
    parent::formAlter($form, $form_state);
  }
}