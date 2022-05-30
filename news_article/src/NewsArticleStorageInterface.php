<?php

namespace Drupal\news_article;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines a common interface for news_article entity controller classes.
 */
interface NewsArticleStorageInterface extends EntityStorageInterface {

  /**
   * Get the most recent news_article posted on the site.
   *
   * @return mixed
   */
  public function getMostRecentNewsArticle();

  /**
   * Find all duplicates of a news_article by matching the title.
   *
   * @param NewsArticleInterface $news_article
   *
   * @return mixed
   */
  public function getNewsArticleDuplicates(NewsArticleInterface $news_article);



}
