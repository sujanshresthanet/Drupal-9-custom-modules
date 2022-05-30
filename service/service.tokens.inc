<?php

/**
 * @file
 * Builds placeholder replacement tokens for values specific to Service nodes.
 */

use Drupal\Core\Render\BubbleableMetadata;

/**
 * Implements hook_token_info().
 */
function service_token_info() {
  $node['duration'] = array(
    'name' => t("Service duration"),
    'description' => t("The length of time the service is set to run."),
  );

  return array(
    'tokens' => array('service' => $node),
  );
}

/**
 * Implements hook_tokens().
 */
function service_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata) {
  $replacements = array();

  if ($type == 'service' && !empty($data['service'])) {
    /** @var \Drupal\service\Entity\Service $service */
    $service = $data['service'];

    foreach ($tokens as $name => $original) {
      switch ($name) {

        case 'duration':
        $replacements[$original] = \Drupal::service('date.formatter')->formatInterval($service->getRuntime());
        break;
      }
    }
  }

  return $replacements;
}
