<?php

namespace Drupal\nnd_component\TwigExtension;

use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;

/**
 * Class DefaultTwigExtension.
 */
class DefaultTwigExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'nnd_component.twig.extension';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction(
        'removeBaseUrl',
        [$this, 'removeBaseUrl']
      ),

    ];
  }

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

}
