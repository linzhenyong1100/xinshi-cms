<?php
/**
 * Created by PhpStorm.
 * User: dadi
 * Date: 2021/4/9
 * Time: 2:44 PM
 */

namespace Drupal\nnd_component\Plugin;

use Drupal\Core\Form\FormStateInterface;

class NodeFormBase extends ContentFormBase {

  /**
   * {@inheritdoc}
   */
  public function formAlter(&$form, FormStateInterface $form_state) {
    // TODO: Implement buildForm() method.
    if (isset($form['meta'])) {
      $form['meta']['#weight'] = 99;
    }
    if (isset($form['meta'])) {
      $form['author']['#weight'] = 98;
    }
    if (isset($form['options'])) {
      $form['options']['#weight'] = 97;
    }
    if (isset($form['path'])) {
      $form['path']['#weight'] = 96;
    }
    if (isset($form['advanced'])) {
      $form['advanced']['#weight'] = 95;
    }
  }
}