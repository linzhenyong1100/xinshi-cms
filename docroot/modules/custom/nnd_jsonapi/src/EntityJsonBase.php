<?php
/**
 * Created by PhpStorm.
 * User: dadi
 * Date: 2021/2/8
 * Time: 4:21 PM
 */

namespace Drupal\nnd_jsonapi;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Component\Serialization\Json;

/**
 * Class EntityJsonBase
 * @package Drupal\nnd_jsonapi
 */
class EntityJsonBase implements EntityJsonInterface {

  /**
   * @var \Drupal\Core\Entity\EntityInterface
   */
  protected $entity;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  function __construct(EntityInterface $entity) {
    $this->entity = $entity;
    $this->entityTypeManager = \Drupal::entityTypeManager();
  }

  /**
   * {@inheritdoc}
   */
  public function getContent() {
    // TODO: Implement getJson() method.
    $data = [];
    $build = $this->entityTypeManager->getViewBuilder($this->entity->getEntityTypeId())->view($this->entity, 'json');
    if ($this->entity->getEntityTypeId() == 'node') {
      \Drupal::service('entity_theme_engine.entity_widget_service')->entityViewAlter($build, $this->entity, 'json');
    }
    unset($build['#prefix']);
    unset($build['#suffix']);
    $content = render($build);
    if ($str = $content->jsonSerialize()) {
      $data = Json::decode(htmlspecialchars_decode($str));
      $this->urlDecodeValue($data);
    }
    return $data ? $data : [];
  }

  /**
   * Set Full text value.
   * @param $data
   */
  private function setFullText(&$data) {
    foreach ($data as $key => &$val) {
      if (is_array($val)) {
        $this->setFullText($val);
      } elseif (is_string($val)) {
        preg_match('/^\[full_text\.\w+\]$/', $val, $matches);
        if ($matches) {
          $field = explode(".", trim($val, ']'))[1];
          if ($this->entity->hasField($field)) {
            $val = $this->entity->get($field)->value;
          }
        }
      }
    }
  }

  /**
   * Set Full text value.
   * @param $data
   */
  private function urlDecodeValue(&$data) {
    foreach ($data as $key => &$val) {
      if (is_array($val)) {
        $this->urlDecodeValue($val);
      } elseif (is_string($val)) {
        $val = htmlspecialchars_decode(urldecode($val));
      }
    }
  }

}