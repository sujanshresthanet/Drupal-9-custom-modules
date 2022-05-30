<?php

namespace Drupal\project;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;

/**
 * Controller class for projects.
 *
 * This extends the default content entity storage class,
 * adding required special handling for project entities.
 */
class ProjectStorage extends SqlContentEntityStorage implements ProjectStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function getProjectDuplicates(ProjectInterface $project) {
    $query = \Drupal::entityQuery('project');
    $query->condition('title', $project->label());

    if ($project->id()) {
      $query->condition('id', $project->id(), '<>');
    }
    return $this->loadMultiple($query->execute());
  }

  /**
   * {@inheritdoc}
   */
  public function getMostRecentProject() {
    $query = \Drupal::entityQuery('project')
    ->condition('status', PROJECT_PUBLISHED)
    ->sort('created', 'DESC')
    ->pager(1);
    return $this->loadMultiple($query->execute());
  }
}
