<?php

namespace Drupal\news_article;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;

/**
 * Controller class for news_articles.
 *
 * This extends the default content entity storage class,
 * adding required special handling for news_article entities.
 */
class NewsArticleStorage extends SqlContentEntityStorage implements NewsArticleStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function getNewsArticleDuplicates(NewsArticleInterface $news_article) {
    $query = \Drupal::entityQuery('news_article');
    $query->condition('title', $news_article->label());

    if ($news_article->id()) {
      $query->condition('id', $news_article->id(), '<>');
    }
    return $this->loadMultiple($query->execute());
  }

  /**
   * {@inheritdoc}
   */
  public function getMostRecentNewsArticle() {
    $query = \Drupal::entityQuery('news_article')
    ->condition('status', NEWSARTICLE_PUBLISHED)
    ->sort('created', 'DESC')
    ->pager(1);
    return $this->loadMultiple($query->execute());
  }
}
