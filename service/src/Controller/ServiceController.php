<?php

namespace Drupal\service\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\service\ServiceInterface;

/**
 * Returns responses for service module routes.
 */
class ServiceController extends ControllerBase {

  /**
   * Route title callback.
   *
   * @param \Drupal\service\ServiceInterface $service
   *   The service entity.
   *
   * @return string
   *   The service label.
   */
  public function serviceTitle(ServiceInterface $service) {
    return $service->label();
  }

}
