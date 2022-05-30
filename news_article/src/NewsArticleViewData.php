<?php

namespace Drupal\news_article;

use Drupal\views\EntityViewsData;

/**
 * Render controller for news_articles.
 */
class NewsArticleViewData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    return $data;
  }

}
