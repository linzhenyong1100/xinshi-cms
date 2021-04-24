<?php

namespace Drupal\nnd_component\Plugin\ContentForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\nnd_component\Plugin\BlockContentFormBase;

/**
 * Class Shuffle
 *
 * @ContentWidgetAnnotation(
 *   id = "content_form_shuffle",
 *   label = @Translation("Shuffle"),
 *   admin_label = @Translation("Shuffle"),
 *   description = @Translation("Shuffle"),
 *   entity = "block_content:shuffle",
 *   plugin_path = {
 *     "type" = "module",
 *     "name" = "content_form",
 *     "directory" = "src/Plugin/ContentForm/Shuffle",
 *   },
 * )
 */
class Shuffle extends BlockContentFormBase {

  /**
   * {@inheritdoc}
   */
  public function formAlter(&$form, FormStateInterface $form_state) {
    // TODO: Implement alter() method.
    parent::formAlter($form, $form_state);
  }
}