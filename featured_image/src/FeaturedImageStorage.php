<?php

namespace Drupal\featured_image;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;

/**
 * Controller class for featured_images.
 *
 * This extends the default content entity storage class,
 * adding required special handling for featured_image entities.
 */
class FeaturedImageStorage extends SqlContentEntityStorage implements FeaturedImageStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function getFeaturedImageDuplicates(FeaturedImageInterface $featured_image) {
    $query = \Drupal::entityQuery('featured_image');
    $query->condition('title', $featured_image->label());

    if ($featured_image->id()) {
      $query->condition('id', $featured_image->id(), '<>');
    }
    return $this->loadMultiple($query->execute());
  }

  /**
   * {@inheritdoc}
   */
  public function getMostRecentFeaturedImage() {
    $query = \Drupal::entityQuery('featured_image')
    ->condition('status', FEATUREDIMAGE_PUBLISHED)
    ->sort('created', 'DESC')
    ->pager(1);
    return $this->loadMultiple($query->execute());
  }
}
