<?php

namespace Drupal\slider;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;

/**
 * Controller class for sliders.
 *
 * This extends the default content entity storage class,
 * adding required special handling for slider entities.
 */
class SliderStorage extends SqlContentEntityStorage implements SliderStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function getSliderDuplicates(SliderInterface $slider) {
    $query = \Drupal::entityQuery('slider');
    $query->condition('title', $slider->label());

    if ($slider->id()) {
      $query->condition('id', $slider->id(), '<>');
    }
    return $this->loadMultiple($query->execute());
  }

  /**
   * {@inheritdoc}
   */
  public function getMostRecentSlider() {
    $query = \Drupal::entityQuery('slider')
    ->condition('status', SLIDER_PUBLISHED)
    ->sort('created', 'DESC')
    ->pager(1);
    return $this->loadMultiple($query->execute());
  }
}
