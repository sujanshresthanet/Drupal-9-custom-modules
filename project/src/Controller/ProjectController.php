<?php

namespace Drupal\project\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\project\ProjectInterface;

/**
 * Returns responses for project module routes.
 */
class ProjectController extends ControllerBase {

  /**
   * Route title callback.
   *
   * @param \Drupal\project\ProjectInterface $project
   *   The project entity.
   *
   * @return string
   *   The project label.
   */
  public function projectTitle(ProjectInterface $project) {
    return $project->label();
  }

}
