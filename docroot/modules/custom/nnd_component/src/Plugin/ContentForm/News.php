<?php

namespace Drupal\nnd_component\Plugin\ContentForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\nnd_component\Plugin\NodeFormBase;

/**
 * Class News
 *
 * @ContentWidgetAnnotation(
 *   id = "content_form_news",
 *   label = @Translation("News"),
 *   admin_label = @Translation("News"),
 *   description = @Translation("News"),
 *   entity = "node:news",
 *   plugin_path = {
 *     "type" = "module",
 *     "name" = "content_form",
 *     "directory" = "src/Plugin/ContentForm/News",
 *   },
 * )
 */
class News extends NodeFormBase {

  /**
   * {@inheritdoc}
   */
  public function formAlter(&$form, FormStateInterface $form_state) {
    // TODO: Implement alter() method.
    parent::formAlter($form, $form_state);
  }
}