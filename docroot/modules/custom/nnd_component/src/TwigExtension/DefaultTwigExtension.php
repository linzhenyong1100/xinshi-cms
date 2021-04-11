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

    ];
  }

}
