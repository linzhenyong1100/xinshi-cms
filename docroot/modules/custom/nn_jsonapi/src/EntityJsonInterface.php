<?php
/**
 * Created by PhpStorm.
 * User: dadi
 * Date: 2021/2/8
 * Time: 4:16 PM
 */

namespace Drupal\nn_jsonapi;

/**
 * Interface EntityJsonInterface
 * @package Drupal\nn_jsonapi
 */
interface EntityJsonInterface {

  /**
   * Return entity json view mode.
   * @return array
   */
  public function getContent();

}