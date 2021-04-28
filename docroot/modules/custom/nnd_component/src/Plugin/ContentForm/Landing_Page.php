<?php

namespace Drupal\nnd_component\Plugin\ContentForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\nnd_component\Plugin\ContentFormBase;

/**
 * Class Landing Page
 *
 * @ContentWidgetAnnotation(
 *   id = "content_form_landing_page",
 *   label = @Translation("Landing Page"),
 *   admin_label = @Translation("Landing Page"),
 *   description = @Translation("Landing Page"),
 *   entity = "node:landing_page",
 *   plugin_path = {
 *     "type" = "module",
 *     "name" = "content_form",
 *     "directory" = "src/Plugin/ContentForm/Landing_Page",
 *   },
 * )
 */
class Landing_Page extends ContentFormBase {

  /**
   * {@inheritdoc}
   */
  public function formAlter(&$form, FormStateInterface $form_state) {
    // TODO: Implement alter() method.
    //$form['is_display_breadcrumb']['#states'] = [
    //  'visible' => [':input[name="banner_style"]' => ['!value' => '_none']],
    //];
    $form['is_display_title']['#states'] = [
      'visible' => [':input[name="banner_style"]' => ['!value' => '_none']],
    ];
    $form['media']['#states'] = [
      'visible' => [':input[name="banner_style"]' => ['!value' => '_none']],
    ];
  }
}