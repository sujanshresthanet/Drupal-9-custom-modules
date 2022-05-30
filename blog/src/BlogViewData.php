<?php

namespace Drupal\blog;

use Drupal\views\EntityViewsData;

/**
 * Render controller for blogs.
 */
class BlogViewData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    return $data;
  }

}
