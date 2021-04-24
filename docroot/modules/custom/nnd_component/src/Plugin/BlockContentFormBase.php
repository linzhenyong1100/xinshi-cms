<?php
/**
 * Created by PhpStorm.
 * User: dadi
 * Date: 2021/4/9
 * Time: 2:44 PM
 */

namespace Drupal\nnd_component\Plugin;

use Drupal\Core\Form\FormStateInterface;

class BlockContentFormBase extends ContentFormBase {

  /**
   * {@inheritdoc}
   */
  public function formAlter(&$form, FormStateInterface $form_state) {
    // TODO: Implement buildForm() method.
    if (isset($form['icon']) && isset($form['title_style'])) {
      $form['icon']['#states'] = [
        'visible' => ['select[name="title_style"]' => ['value' => 'style-v2']],
      ];
      $form['icon']['widget'][0]['value']['#states'] = [
        'required' => ['select[name="title_style"]' => ['value' => 'style-v2']],
      ];
    }
  }
}