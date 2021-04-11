<?php
/**
 * Created by PhpStorm.
 * User: dadi
 * Date: 2021/4/9
 * Time: 10:53 AM
 */

namespace Drupal\nnd_component\Plugin;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Plugin\PluginInspectionInterface;

interface ContentFormInterface extends PluginInspectionInterface {

  /**
   * Alter form.
   * @param $form
   * @param FormStateInterface $form_state
   * @return mixed
   */
  public function formAlter(&$form, FormStateInterface $form_state);
}