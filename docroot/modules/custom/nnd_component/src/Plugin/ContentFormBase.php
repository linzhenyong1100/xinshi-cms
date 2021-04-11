<?php
/**
 * Created by PhpStorm.
 * User: dadi
 * Date: 2021/4/9
 * Time: 2:44 PM
 */

namespace Drupal\nnd_component\Plugin;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Form\FormStateInterface;

class ContentFormBase extends PluginBase implements ContentFormInterface {



  /**
   * {@inheritdoc}
   */
  public function formAlter(&$form, FormStateInterface $form_state) {
    // TODO: Implement buildForm() method.
  }
}