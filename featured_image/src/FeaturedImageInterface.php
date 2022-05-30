<?php

namespace Drupal\featured_image;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface defining an featured_image entity.
 */
interface FeaturedImageInterface extends ContentEntityInterface {

  /**
   * Sets the title for the featured_image.
   *
   * @param string $title
   *   The short title of the feed.
   *
   * @return \Drupal\featured_image\FeaturedImageInterface
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
   * @return \Drupal\featured_image\FeaturedImageInterface
   *   The class instance that this method is called on.
   */
  public function setCreated($created);


  /**
   * Returns if the featured_image is open.
   *
   * @return bool
   *   TRUE if the featured_image is open.
   */
  public function isOpen();

  /**
   * Returns if the featured_image is closed.
   *
   * @return bool
   *   TRUE if the featured_image is closed.
   */
  public function isClosed();

  /**
   * Sets the featured_image to closed.
   */
  public function close();

  /**
   * Sets the featured_image to open.
   */
  public function open();
}
