<?php

namespace Drupal\blog;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines a common interface for blog entity controller classes.
 */
interface BlogStorageInterface extends EntityStorageInterface {

  /**
   * Get the most recent blog posted on the site.
   *
   * @return mixed
   */
  public function getMostRecentBlog();

  /**
   * Find all duplicates of a blog by matching the title.
   *
   * @param BlogInterface $blog
   *
   * @return mixed
   */
  public function getBlogDuplicates(BlogInterface $blog);



}
