<?php

namespace Drupal\nnd_jsonapi\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\nnd_jsonapi\NodeJson;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;


/**
 * Created by PhpStorm.
 * User: dadi
 * Date: 2021/1/25
 * Time: 9:40 PM
 */
class ApiController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Return content json.
   * @return JsonResponse
   */
  public function contentJson() {
    $data = [];
    if ($entity = $this->getEntityByQuery()) {
      try {
        switch ($entity->getEntityTypeId()) {
          case 'node':
            $json = new NodeJson($entity);
            $data = $json->getContent();
        }
      } catch (\Exception $e) {
      }
    }
    return new JsonResponse($data);
  }

  /**
   * Return landing page json.
   * @return JsonResponse
   */
  public function landingPageJson() {
    $data = [];
    if ($entity = $this->getEntityByQuery()) {
      try {
        switch ($entity->getEntityTypeId()) {
          case 'node':
            $json = new NodeJson($entity);
            $data = $json->getContent();
        }
      } catch (\Exception $e) {
      }
    }
    return new JsonResponse($data);
  }

  /**
   * @param string $key
   * @return \Drupal\Core\Entity\EntityInterface|null|static
   */
  private function getEntityByQuery($key = 'content') {
    $id = '';
    //get node id
    $path = \Drupal::request()->get($key);
    try {
      $base = \Drupal::request()->getBaseUrl();
      $path = str_replace([$base], '', $path);
      $path = '/' . trim($path, '/');
      $path = \Drupal::service('path.alias_manager')->getPathByAlias($path);
      if (preg_match('/node\/(\d+)/', $path, $matches)) {
        $id = $matches[1];
      }
    } catch (\Exception $e) {

    }
    $entity = (empty($id) == FALSE && is_numeric($id)) ? Node::load($id) : NULL;

    return $entity;
  }

}