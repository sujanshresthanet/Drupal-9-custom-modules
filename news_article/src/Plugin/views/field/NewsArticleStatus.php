<?php

namespace Drupal\news_article\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;


/**
 * Field handler which displays the flag indicating whether the news_article is active
 *
 * @ViewsField("news_article_status")
 */
class NewsArticleStatus extends FieldPluginBase {

  /**
   * @param \Drupal\views\ResultRow $values
   * @return mixed
   */
  function render(ResultRow $values) {
    /** @var \Drupal\news_article\NewsArticleInterface $entity */
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
