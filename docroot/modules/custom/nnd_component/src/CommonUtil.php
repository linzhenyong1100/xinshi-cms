<?php

namespace Drupal\nnd_component;

use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;

/**
 * Created by PhpStorm.
 * User: dadi
 * Date: 2021/4/19
 * Time: 10:03 PM
 */
class CommonUtil {

  /**
   * Remove url's base root.
   * @param $url
   * @return bool|string
   */
  public static function removeBaseUrl($url) {
    if (empty($url)) {
      return $url;
    }
    $base_root = $GLOBALS['base_root'];
    if (strpos($url, $base_root) === 0) {
      $url = substr($url, strlen($base_root));
    }
    return $url;
  }

  /**
   * Return image style url.
   * @param $fid
   * @param string $style
   * @param bool $remove_base_url
   * @return \Drupal\Core\GeneratedUrl|string
   */
  public static function getImageStyle($fid, $style = 'crop', $remove_base_url = TRUE) {
    if ($fid && $file = File::load($fid)) {
      if ($remove_base_url) {
        return self::removeBaseUrl(ImageStyle::load($style)->buildUrl($file->getFileUri()));
      } else {
        return ImageStyle::load($style)->buildUrl($file->getFileUri());
      }
    } else {
      return '';
    }
  }
}