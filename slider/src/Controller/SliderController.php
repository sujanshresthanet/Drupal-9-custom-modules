<?php

namespace Drupal\slider\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\slider\SliderInterface;

/**
 * Returns responses for slider module routes.
 */
class SliderController extends ControllerBase {

  /**
   * Route title callback.
   *
   * @param \Drupal\slider\SliderInterface $slider
   *   The slider entity.
   *
   * @return string
   *   The slider label.
   */
  public function sliderTitle(SliderInterface $slider) {
    return $slider->label();
  }

}
