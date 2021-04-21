<?php
/**
 * Created by PhpStorm.
 * User: dadi
 * Date: 2021/2/8
 * Time: 4:29 PM
 */

namespace Drupal\nnd_jsonapi;

use Drupal\nnd_component\CommonUtil;

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
    $this->getBanner($data);
    foreach ($displays['blocks'] as $display) {
      switch ($display['provider']) {
        case 'block_content':
          $block = $this->entityTypeManager->getStorage($display['provider'])->loadByProperties(['uuid' => explode(':', $display['id'])[1]]);
          if ($block) {
            $entityJson = new EntityJsonBase(current($block));
            $widgets[] = [
              'weight' => $display['weight'],
              'content' => $entityJson->getContent(),
              'type' => $entityJson->entity->bundle(),
            ];
          }
          break;
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

  /**
   * Set banner data.
   * @param $data
   */
  private function getBanner(&$data) {
    if (empty($this->entity->get('is_display_title')->value)) {
      return;
    }
    $banner['type'] = 'banner-simple';
    $banner['style'] = $this->entity->get('banner_style')->isEmpty() ? 'normal' : $this->entity->get('banner_style')->value;

    $media = $this->entity->get('media')->entity;
    $banner['bannerBg'] = [
      'classes' => 'bg-fill-width',
      'img' => [
        'hostClasses' => 'bg-center',
        'src' => $media ? CommonUtil::getImageStyle($media->get('field_media_image')->target_id) : '',
        'alt' => $media ? $media->label() : '',
      ],
    ];
    $banner['title'] = $this->entity->label();
    $banner['breadcrumb'] = [
    ];
    $data['body'][] = $banner;
  }

}