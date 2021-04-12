<?php
/**
 * Created by PhpStorm.
 * User: dadi
 * Date: 2021/2/8
 * Time: 4:29 PM
 */

namespace Drupal\nnd_jsonapi;

use Drupal\block_content\Entity\BlockContent;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Render\Markup;

/**
 * Class NodeJson
 * @package Drupal\nnd_jsonapi
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
    $data['body'] = [];
    $widgets = [];
    foreach ($displays['blocks'] as $display) {
      $block = $this->entityTypeManager->getStorage($display['provider'])->loadByProperties(['uuid' => explode(":", $display['id'])[1]]);
      if ($block) {
        $entityJson = new EntityJsonBase(current($block));
        $widgets[] = [
          'weight' => $display['weight'],
          'content' => $entityJson->getContent(),
          'type' => $entityJson->entity->bundle(),
        ];
      }
    }
    //Sort widgets by weight
    array_multisort($widgets, SORT_ASC, SORT_NUMERIC, array_column($widgets, 'weight'));
    foreach ($widgets as $widget) {
      $item = $widget['content'];
      if ($widget['type'] == 'json' && isset($item['body']) && is_array($item['body'])) {
        //multi widgets 
        $data['body'] = array_merge($data['body'], $item['body']);
      } else {
        $data['body'][] = $item;
      }
    }
    return $data;
  }

}