<?php

namespace Drupal\slider\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;


/**
 * Field handler which displays the flag indicating whether the slider is active
 *
 * @ViewsField("slider_status")
 */
class SliderStatus extends FieldPluginBase {

  /**
   * @param \Drupal\views\ResultRow $values
   * @return mixed
   */
  function render(ResultRow $values) {
    /** @var \Drupal\slider\SliderInterface $entity */
    $entity = $values->_entity;

    if ($entity->isOpen() && $entity->getRuntime() != 0) {
      $date = \Drupal::service('date.formatter')->format($entity->getCreated() + $entity->getRuntime(), 'short');
      $output = 'Yes (until ' . rtrim(strstr($date, '-', true)) . ')';
    }
    else if ($entity->isOpen()) {
      $output = t('Yes');
    }
    else {
     $output = 'No';
    }

    return $output;
  }
}
