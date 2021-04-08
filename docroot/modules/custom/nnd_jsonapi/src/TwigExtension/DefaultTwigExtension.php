<?php

namespace Drupal\nnd_jsonapi\TwigExtension;

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
        'jsonSpecialChars',
        [$this, 'jsonSpecialChars']
      ),

    ];
  }

  /**
   * @param $str
   * @return mixed
   */
  public function jsonSpecialChars($str) {
    $str = str_replace(["\n"], "\\\\n", $str);
    $str = str_replace(["\r"], "", $str);
    $str = str_replace(["\""], "\\\"", $str);
    $str = str_replace(["\'"], "\\'", $str);
    return $str;
  }

}
