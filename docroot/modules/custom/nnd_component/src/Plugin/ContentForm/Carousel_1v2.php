<?php

namespace Drupal\nnd_component\Plugin\ContentForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\nnd_component\Plugin\ContentFormBase;

/**
 * Class Carousel_1v2
 *
 * @ContentWidgetAnnotation(
 *   id = "content_form_carousel_1v2",
 *   label = @Translation("Carousel 1v2"),
 *   admin_label = @Translation("Carousel 1v2"),
 *   description = @Translation("Carousel 1v2"),
 *   entity = "block_content:carousel_1v2",
 *   plugin_path = {
 *     "type" = "module",
 *     "name" = "content_form",
 *     "directory" = "src/Plugin/ContentForm/Carousel_1v2",
 *   },
 * )
 */
class Carousel_1v2 extends ContentFormBase {

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