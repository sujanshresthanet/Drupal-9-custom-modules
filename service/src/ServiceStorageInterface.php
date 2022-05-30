<?php

namespace Drupal\service;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines a common interface for service entity controller classes.
 */
interface ServiceStorageInterface extends EntityStorageInterface {

  /**
   * Get the most recent service posted on the site.
   *
   * @return mixed
   */
  public function getMostRecentService();

  /**
   * Find all duplicates of a service by matching the title.
   *
   * @param ServiceInterface $service
   *
   * @return mixed
   */
  public function getServiceDuplicates(ServiceInterface $service);



}
