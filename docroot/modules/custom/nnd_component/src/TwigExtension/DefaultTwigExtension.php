<?php

namespace Drupal\nnd_component\TwigExtension;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;
use Drupal\nnd_component\CommonUtil;
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
      new \Twig_SimpleFunction(
        'imageStyle',
        [$this, 'getImageStyle']
      ),
      new \Twig_SimpleFunction(
        'timestampFormat',
        [$this, 'timestampFormat']
      ),
      new \Twig_SimpleFunction(
        'entityUrl',
        [$this, 'entityUrl']
      ),
    ];
  }

  /**
   * Remove url's base root.
   * @param $url
   * @return bool|string
   */
  public function removeBaseUrl($url) {
    return CommonUtil::removeBaseUrl($url);
  }

  /**
   * @param $fid
   * @param string $style
   * @param bool $remove_base_url
   * @return \Drupal\Core\GeneratedUrl|string
   */
  public function getImageStyle($fid, $style = 'crop', $remove_base_url = TRUE) {
    return CommonUtil::getImageStyle($fid, $style, $remove_base_url);
  }

  /**
   * Return date string.
   * @param $timestamp
   * @param string $format
   * @return false|string
   */
  public function timestampFormat($timestamp, $format = 'Y-m-d H:i:s') {
    return date($format, $timestamp);
  }

  public function entityUrl(EntityInterface $entity) {
    return $entity->toUrl()->toString();
  }

}
