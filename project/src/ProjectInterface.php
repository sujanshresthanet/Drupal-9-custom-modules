<?php

namespace Drupal\project;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface defining an project entity.
 */
interface ProjectInterface extends ContentEntityInterface {

  /**
   * Sets the title for the project.
   *
   * @param string $title
   *   The short title of the feed.
   *
   * @return \Drupal\project\ProjectInterface
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
   * @return \Drupal\project\ProjectInterface
   *   The class instance that this method is called on.
   */
  public function setCreated($created);


  /**
   * Returns if the project is open.
   *
   * @return bool
   *   TRUE if the project is open.
   */
  public function isOpen();

  /**
   * Returns if the project is closed.
   *
   * @return bool
   *   TRUE if the project is closed.
   */
  public function isClosed();

  /**
   * Sets the project to closed.
   */
  public function close();

  /**
   * Sets the project to open.
   */
  public function open();
}
