<?php

namespace Drupal\news_article\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\news_article\NewsArticleInterface;

/**
 * Returns responses for news_article module routes.
 */
class NewsArticleController extends ControllerBase {

  /**
   * Route title callback.
   *
   * @param \Drupal\news_article\NewsArticleInterface $news_article
   *   The news_article entity.
   *
   * @return string
   *   The news_article label.
   */
  public function news_articleTitle(NewsArticleInterface $news_article) {
    return $news_article->label();
  }

}
