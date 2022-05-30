<?php

namespace Drupal\project;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines a common interface for project entity controller classes.
 */
interface ProjectStorageInterface extends EntityStorageInterface {

  /**
   * Get the most recent project posted on the site.
   *
   * @return mixed
   */
  public function getMostRecentProject();

  /**
   * Find all duplicates of a project by matching the title.
   *
   * @param ProjectInterface $project
   *
   * @return mixed
   */
  public function getProjectDuplicates(ProjectInterface $project);



}
