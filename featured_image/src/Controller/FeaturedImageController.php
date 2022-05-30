<?php

namespace Drupal\featured_image\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\featured_image\FeaturedImageInterface;

/**
 * Returns responses for featured_image module routes.
 */
class FeaturedImageController extends ControllerBase {

  /**
   * Route title callback.
   *
   * @param \Drupal\featured_image\FeaturedImageInterface $featured_image
   *   The featured_image entity.
   *
   * @return string
   *   The featured_image label.
   */
  public function featured_imageTitle(FeaturedImageInterface $featured_image) {
    return $featured_image->label();
  }

}
