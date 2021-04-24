<?php

namespace Drupal\nnd_component\Plugin\ContentForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\nnd_component\Plugin\BlockContentFormBase;

/**
 * Class Video Background
 *
 * @ContentWidgetAnnotation(
 *   id = "content_form_video_bg",
 *   label = @Translation("Video_Background"),
 *   admin_label = @Translation("Video_Background"),
 *   description = @Translation("Video_Background"),
 *   entity = "block_content:video_bg",
 *   plugin_path = {
 *     "type" = "module",
 *     "name" = "content_form",
 *     "directory" = "src/Plugin/ContentForm/Video_Background",
 *   },
 * )
 */
class Video_Background extends BlockContentFormBase {

  /**
   * {@inheritdoc}
   */
  public function formAlter(&$form, FormStateInterface $form_state) {
    // TODO: Implement alter() method.
    parent::formAlter($form, $form_state);
  }
}