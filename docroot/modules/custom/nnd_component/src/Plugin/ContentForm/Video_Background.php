<?php

namespace Drupal\nnd_component\Plugin\ContentForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\nnd_component\Plugin\ContentFormBase;

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
class Video_Background extends ContentFormBase {

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