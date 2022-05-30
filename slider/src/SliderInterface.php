<?php

namespace Drupal\slider;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface defining an slider entity.
 */
interface SliderInterface extends ContentEntityInterface {

  /**
   * Sets the title for the slider.
   *
   * @param string $title
   *   The short title of the feed.
   *
   * @return \Drupal\slider\SliderInterface
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
   * @return \Drupal\slider\SliderInterface
   *   The class instance that this method is called on.
   */
  public function setCreated($created);


  /**
   * Returns if the slider is open.
   *
   * @return bool
   *   TRUE if the slider is open.
   */
  public function isOpen();

  /**
   * Returns if the slider is closed.
   *
   * @return bool
   *   TRUE if the slider is closed.
   */
  public function isClosed();

  /**
   * Sets the slider to closed.
   */
  public function close();

  /**
   * Sets the slider to open.
   */
  public function open();
}
