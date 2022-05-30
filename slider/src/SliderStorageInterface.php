<?php

namespace Drupal\slider;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines a common interface for slider entity controller classes.
 */
interface SliderStorageInterface extends EntityStorageInterface {

  /**
   * Get the most recent slider posted on the site.
   *
   * @return mixed
   */
  public function getMostRecentSlider();

  /**
   * Find all duplicates of a slider by matching the title.
   *
   * @param SliderInterface $slider
   *
   * @return mixed
   */
  public function getSliderDuplicates(SliderInterface $slider);



}
