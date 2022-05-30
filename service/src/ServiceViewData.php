<?php

namespace Drupal\service;

use Drupal\views\EntityViewsData;

/**
 * Render controller for services.
 */
class ServiceViewData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    return $data;
  }

}
