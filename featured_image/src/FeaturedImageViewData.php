<?php

namespace Drupal\featured_image;

use Drupal\views\EntityViewsData;

/**
 * Render controller for featured_images.
 */
class FeaturedImageViewData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    return $data;
  }

}
