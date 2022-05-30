<?php

/**
 * @file
 * Builds placeholder replacement tokens for values specific to Project nodes.
 */

use Drupal\Core\Render\BubbleableMetadata;

/**
 * Implements hook_token_info().
 */
function project_token_info() {
  $node['duration'] = array(
    'name' => t("Project duration"),
    'description' => t("The length of time the project is set to run."),
  );

  return array(
    'tokens' => array('project' => $node),
  );
}

/**
 * Implements hook_tokens().
 */
function project_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata) {
  $replacements = array();

  if ($type == 'project' && !empty($data['project'])) {
    /** @var \Drupal\project\Entity\Project $project */
    $project = $data['project'];

    foreach ($tokens as $name => $original) {
      switch ($name) {

        case 'duration':
        $replacements[$original] = \Drupal::service('date.formatter')->formatInterval($project->getRuntime());
        break;
      }
    }
  }

  return $replacements;
}
