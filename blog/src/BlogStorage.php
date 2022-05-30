<?php

namespace Drupal\blog;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;

/**
 * Controller class for blogs.
 *
 * This extends the default content entity storage class,
 * adding required special handling for blog entities.
 */
class BlogStorage extends SqlContentEntityStorage implements BlogStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function getBlogDuplicates(BlogInterface $blog) {
    $query = \Drupal::entityQuery('blog');
    $query->condition('title', $blog->label());

    if ($blog->id()) {
      $query->condition('id', $blog->id(), '<>');
    }
    return $this->loadMultiple($query->execute());
  }

  /**
   * {@inheritdoc}
   */
  public function getMostRecentBlog() {
    $query = \Drupal::entityQuery('blog')
    ->condition('status', BLOG_PUBLISHED)
    ->sort('created', 'DESC')
    ->pager(1);
    return $this->loadMultiple($query->execute());
  }
}
