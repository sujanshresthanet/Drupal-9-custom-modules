<?php

namespace Drupal\service;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;

/**
 * Controller class for services.
 *
 * This extends the default content entity storage class,
 * adding required special handling for service entities.
 */
class ServiceStorage extends SqlContentEntityStorage implements ServiceStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function getServiceDuplicates(ServiceInterface $service) {
    $query = \Drupal::entityQuery('service');
    $query->condition('title', $service->label());

    if ($service->id()) {
      $query->condition('id', $service->id(), '<>');
    }
    return $this->loadMultiple($query->execute());
  }

  /**
   * {@inheritdoc}
   */
  public function getMostRecentService() {
    $query = \Drupal::entityQuery('service')
    ->condition('status', SERVICE_PUBLISHED)
    ->sort('created', 'DESC')
    ->pager(1);
    return $this->loadMultiple($query->execute());
  }
}
