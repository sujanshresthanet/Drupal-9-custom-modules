<?php

namespace Drupal\service;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface defining an service entity.
 */
interface ServiceInterface extends ContentEntityInterface {

  /**
   * Sets the title for the service.
   *
   * @param string $title
   *   The short title of the feed.
   *
   * @return \Drupal\service\ServiceInterface
   *   The class instance that this method is called on.
   */
  public function setTitle($title);

  /**
   * Return when the feed was modified last time.
   *
   * @return int
   *   The timestamp of the last time the feed was modified.
   */
  public function getCreated();

  /**
   * Sets the last modification of the feed.
   *
   * @param int $created
   *   The timestamp when the feed was modified.
   *
   * @return \Drupal\service\ServiceInterface
   *   The class instance that this method is called on.
   */
  public function setCreated($created);


  /**
   * Returns if the service is open.
   *
   * @return bool
   *   TRUE if the service is open.
   */
  public function isOpen();

  /**
   * Returns if the service is closed.
   *
   * @return bool
   *   TRUE if the service is closed.
   */
  public function isClosed();

  /**
   * Sets the service to closed.
   */
  public function close();

  /**
   * Sets the service to open.
   */
  public function open();
}
