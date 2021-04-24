<?php

namespace Drupal\nnd_component\Plugin\ContentForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\nnd_component\Plugin\BlockContentFormBase;

/**
 * Class Carousel_2v1
 *
 * @ContentWidgetAnnotation(
 *   id = "content_form_carousel_2v1",
 *   label = @Translation("Carousel 2v1"),
 *   admin_label = @Translation("Carousel 2v1"),
 *   description = @Translation("Carousel 2v1"),
 *   entity = "block_content:carousel_2v1",
 *   plugin_path = {
 *     "type" = "module",
 *     "name" = "content_form",
 *     "directory" = "src/Plugin/ContentForm/Carousel_2v1",
 *   },
 * )
 */
class Carousel_2v1 extends BlockContentFormBase {

  /**
   * {@inheritdoc}
   */
  public function formAlter(&$form, FormStateInterface $form_state) {
    // TODO: Implement alter() method.
    parent::formAlter($form, $form_state);
  }
}