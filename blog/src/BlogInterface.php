<?php

namespace Drupal\blog;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface defining an blog entity.
 */
interface BlogInterface extends ContentEntityInterface {

  /**
   * Sets the title for the blog.
   *
   * @param string $title
   *   The short title of the feed.
   *
   * @return \Drupal\blog\BlogInterface
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
   * @return \Drupal\blog\BlogInterface
   *   The class instance that this method is called on.
   */
  public function setCreated($created);


  /**
   * Returns if the blog is open.
   *
   * @return bool
   *   TRUE if the blog is open.
   */
  public function isOpen();

  /**
   * Returns if the blog is closed.
   *
   * @return bool
   *   TRUE if the blog is closed.
   */
  public function isClosed();

  /**
   * Sets the blog to closed.
   */
  public function close();

  /**
   * Sets the blog to open.
   */
  public function open();
}
