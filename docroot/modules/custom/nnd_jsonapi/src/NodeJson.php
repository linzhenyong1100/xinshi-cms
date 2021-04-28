<?php
/**
 * Created by PhpStorm.
 * User: dadi
 * Date: 2021/2/8
 * Time: 4:29 PM
 */

namespace Drupal\nnd_jsonapi;

use Drupal\nnd_component\CommonUtil;
use Drupal\Component\Serialization\Json;

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
        return $this->getNodeContent();
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
    if ($this->entity->get('banner_style')->isEmpty()) {
      return;
    }
    $banner['type'] = 'banner-simple';
    $banner['style'] = $this->entity->get('banner_style')->isEmpty() ? 'normal' : $this->entity->get('banner_style')->value;

    $media = $this->entity->get('media')->entity;
    $img = $media ? CommonUtil::getImageStyle($media->get('field_media_image')->target_id) : '';
    if ($img) {
      $banner['bannerBg'] = [
        'classes' => 'bg-fill-width',
        'img' => [
          'hostClasses' => 'bg-center',
          'src' => $img,
          'alt' => $media ? $media->label() : '',
        ],
      ];
    } else {
      $banner['style'] = 'no-bg';
    }
    $banner['title'] = $this->entity->get('is_display_title')->value ? $this->entity->label() : '';
    $banner['breadcrumb'] = [
    ];
    $data['body'][] = $banner;
  }

  private function getNodeContent() {
    $data = [];
    $build = $this->entityTypeManager->getViewBuilder($this->entity->getEntityTypeId())->view($this->entity);

    $panels = [];
    if (isset($build['#panels_display']) && isset($build['content']['content'])) {
      foreach ($build['content']['content'] as $key => $content) {
        if (strpos($key, '#') === 0) {
          continue;
        }
        switch ($content['#base_plugin_id']) {
          case 'views_block':
            $panels[$content['content']['#name'] . '_' . $content['content']['#display_id']] = [
              'rows' => $content['content']['view_build']['#rows'][0]['#rows'],
              'title' => $content['#configuration']['views_label'] ? $content['#configuration']['views_label'] : $content['content']['#title']['#markup']];
            break;
        }
      }
    }
    \Drupal::service('entity_theme_engine.entity_widget_service')->entityViewAlter($build, $this->entity, 'json');
    foreach ($panels as $key => $panel) {
      $build['content']['#context'][$key] = $panel;
    }
    unset($build['#prefix']);
    unset($build['#suffix']);
    $content = render($build);
    if ($str = $content->jsonSerialize()) {
      $data = Json::decode(htmlspecialchars_decode($str));
      parent::setFullText($data);
    }
    return $data ? $data : [];
  }


}