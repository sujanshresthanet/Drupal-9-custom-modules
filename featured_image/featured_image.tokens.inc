<?php

/**
 * @file
 * Builds placeholder replacement tokens for values specific to FeaturedImage nodes.
 */

use Drupal\Core\Render\BubbleableMetadata;

/**
 * Implements hook_token_info().
 */
function featured_image_token_info() {
  $node['duration'] = array(
    'name' => t("FeaturedImage duration"),
    'description' => t("The length of time the featured_image is set to run."),
  );

  return array(
    'tokens' => array('featured_image' => $node),
  );
}

/**
 * Implements hook_tokens().
 */
function featured_image_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata) {
  $replacements = array();

  if ($type == 'featured_image' && !empty($data['featured_image'])) {
    /** @var \Drupal\featured_image\Entity\FeaturedImage $featured_image */
    $featured_image = $data['featured_image'];

    foreach ($tokens as $name => $original) {
      switch ($name) {

        case 'duration':
        $replacements[$original] = \Drupal::service('date.formatter')->formatInterval($featured_image->getRuntime());
        break;
      }
    }
  }

  return $replacements;
}
