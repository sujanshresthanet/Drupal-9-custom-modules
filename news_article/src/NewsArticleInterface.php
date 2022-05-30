<?php

namespace Drupal\news_article;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface defining an news_article entity.
 */
interface NewsArticleInterface extends ContentEntityInterface {

  /**
   * Sets the title for the news_article.
   *
   * @param string $title
   *   The short title of the feed.
   *
   * @return \Drupal\news_article\NewsArticleInterface
   *   The class instance that this method is called on.
   */
  public function setTitle($title);

  /**
   * Return when the feed was modified last time.
   *
   * @return int
   *   The timestamp of the last time the feed was modified.
   */
  public function getCreated();

  /**
   * Sets the last modification of the feed.
   *
   * @param int $created
   *   The timestamp when the feed was modified.
   *
   * @return \Drupal\news_article\NewsArticleInterface
   *   The class instance that this method is called on.
   */
  public function setCreated($created);


  /**
   * Returns if the news_article is open.
   *
   * @return bool
   *   TRUE if the news_article is open.
   */
  public function isOpen();

  /**
   * Returns if the news_article is closed.
   *
   * @return bool
   *   TRUE if the news_article is closed.
   */
  public function isClosed();

  /**
   * Sets the news_article to closed.
   */
  public function close();

  /**
   * Sets the news_article to open.
   */
  public function open();
}
