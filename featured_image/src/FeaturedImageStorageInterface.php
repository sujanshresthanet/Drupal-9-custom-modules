<?php

namespace Drupal\featured_image;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines a common interface for featured_image entity controller classes.
 */
interface FeaturedImageStorageInterface extends EntityStorageInterface {

  /**
   * Get the most recent featured_image posted on the site.
   *
   * @return mixed
   */
  public function getMostRecentFeaturedImage();

  /**
   * Find all duplicates of a featured_image by matching the title.
   *
   * @param FeaturedImageInterface $featured_image
   *
   * @return mixed
   */
  public function getFeaturedImageDuplicates(FeaturedImageInterface $featured_image);



}
