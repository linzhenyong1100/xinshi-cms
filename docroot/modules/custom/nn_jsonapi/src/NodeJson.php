<?php
/**
 * Created by PhpStorm.
 * User: dadi
 * Date: 2021/2/8
 * Time: 4:29 PM
 */

namespace Drupal\nn_jsonapi;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Render\Markup;

/**
 * Class NodeJson
 * @package Drupal\nn_jsonapi
 */
class NodeJson extends EntityJsonBase {

  /**
   * {@inheritdoc}
   */
  public function getContent() {
    // TODO: Implement getJson() method.
    switch ($this->entity->bundle()) {
      case 'landing_page':
        return $this->landingPageType();
        break;
      default:
        return parent::getContent();
    }
  }

  /**
   * Return the content of json.
   * @return array
   */
  private function landingPageType() {
    $displays = $this->entity->get('panelizer')->panels_display;
    $data['title'] = $this->entity->label();
    foreach ($displays['blocks'] as $display) {
      $block = $this->entityTypeManager->getStorage($display['provider'])->loadByProperties(['uuid' => explode(":", $display['id'])[1]]);
      if ($block) {
        $entityJson = new EntityJsonBase(current($block));
        $data['body'][] = $entityJson->getContent();
      }
    }
    return $data;
  }

}