<?php

namespace Drupal\dyniva_editor_panelizer\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('panels_ipe.block_content_types')) {
      $route->setDefaults([
        '_controller' => '\Drupal\dyniva_editor_panelizer\Controller\PanelsIPEPageController::getBlockContentTypes',
      ]);
    }
    if ($route = $collection->get('panels_ipe.block_plugins')) {
      $route->setDefaults([
        '_controller' => '\Drupal\dyniva_editor_panelizer\Controller\PanelsIPEPageController::getBlockPlugins',
      ]);
    }
    if ($route = $collection->get('panels_ipe.block_content_existing.form')) {
      $route->setPath('/admin/panels_ipe/variant/{panels_storage_type}/{panels_storage_id}/block_content/{type}/block/{block_uuid}/{block_content_uuid}/form');
      $route->setDefaults([
        '_controller' => '\Drupal\dyniva_editor_panelizer\Controller\PanelsIPEPageController::getBlockContentForm',
      ]);
    }
  }

}
