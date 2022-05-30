<?php

/**
 * @file
 * Builds placeholder replacement tokens for values specific to Slider nodes.
 */

use Drupal\Core\Render\BubbleableMetadata;

/**
 * Implements hook_token_info().
 */
function slider_token_info() {
  $node['duration'] = array(
    'name' => t("Slider duration"),
    'description' => t("The length of time the slider is set to run."),
  );

  return array(
    'tokens' => array('slider' => $node),
  );
}

/**
 * Implements hook_tokens().
 */
function slider_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata) {
  $replacements = array();

  if ($type == 'slider' && !empty($data['slider'])) {
    /** @var \Drupal\slider\Entity\Slider $slider */
    $slider = $data['slider'];

    foreach ($tokens as $name => $original) {
      switch ($name) {

        case 'duration':
        $replacements[$original] = \Drupal::service('date.formatter')->formatInterval($slider->getRuntime());
        break;
      }
    }
  }

  return $replacements;
}
